import cv2
import time
import ftplib
import dlib
import os
import threading
import mysql.connector
import requests
import json
from RPi_I2C_LCD_driver import RPi_I2C_driver
import RPi.GPIO as GPIO
import numpy as np

#파일 위치 및 사진저장 기본경로지정
PATH = '/home/gt/Desktop/gttest/finally/my_face'
os.chdir(PATH)

#lcd
lcd = RPi_I2C_driver.lcd(0x27)

#키패드 행, 열 정의
L1 = 5
L2 = 6
L3 = 13
C1 = 12
C2 = 16
C3 = 20
C4 = 21

#사용할 기본변수
pinRELAY = 18
keypadPressed = -1
count = 0
rcount = 0
lcdkey = 0
input = ""
current_time = time.strftime("%Y-%m-%d %H:%M:%S")

#얼굴인식 설정
detector = dlib.get_frontal_face_detector()
landmark_predictor = dlib.shape_predictor(os.path.join(PATH, 'shape_predictor_68_face_landmarks.dat'))
face_rec_model = dlib.face_recognition_model_v1(os.path.join(PATH, 'dlib_face_recognition_resnet_model_v1.dat'))

def shape_to_np(dlib_shape):
    return np.array([(dlib_shape.part(i).x, dlib_shape.part(i).y) for i in range(68)])

def detect_face_embedding(img, landmark_detector, face_recognizer):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    rects = detector(gray, 1)

    if len(rects) < 1:
        return None, None

    # 첫 번째 인식된 얼굴 사용
    rect = rects[0]
    shape = landmark_detector(gray, rect)
    landmarks = shape_to_np(shape)
    face_descriptor = face_recognizer.compute_face_descriptor(img, shape)

    return landmarks, face_descriptor

#data 전송부 세팅
def datasend(table1, colom1, colom2, data1, data2):
    connection = mysql.connector.connect(
        host = "zxz0608.cafe24.com",
        user = "zxz0608",
        password = "daelim2023!",
        database = "zxz0608"
    )
    cursor = connection.cursor()

    sql = "INSERT INTO "+table1+" (" + colom1+", " + colom2+") VALUES (%s, %s)"
    data = (data1, data2)
    cursor.execute(sql, data)
    connection.commit()

    cursor.close()
    connection.close()

#사진data 전송부 세팅
def sendphoto(osphoto, file):
    ftp = ftplib.FTP()
    ftp.connect("183.111.138.229",21)
    ftp.login("zxz0608","daelim2023!")
    ftp.cwd(osphoto)
    os.chdir(r'/home/gt/Desktop/gttest/finally/my_face')
    myfile = open(file,'rb')
    ftp.storbinary('STOR '+file, myfile)
    myfile.close()
    ftp.close()

#data 수신부 세팅
def datarecv(table2):
    connection = mysql.connector.connect(
        host = "zxz0608.cafe24.com",
        user = "zxz0608",
        password = "daelim2023!",
        database = "zxz0608"
    )
    cursor = connection.cursor()
    
    sql = "SELECT * FROM " + table2
    cursor.execute(sql)
    results = cursor.fetchall()
    for row in results:
        second_value = row[1]
    
    cursor.close()
    connection.close()
    return second_value

#사진촬영 자동 실행 방지
datasend(table1='camera', colom1='date', colom2='tp', data1=current_time, data2='0')

#kakaotalk 세팅
def kakao(txt):
    with open(r"/home/gt/Desktop/gttest/output.json","r") as fp:
        tokens = json.load(fp)

    friend_url = "https://kapi.kakao.com/v1/api/talk/friends"
    headers={"Authorization" : "Bearer " + tokens["access_token"]}
    result = json.loads(requests.get(friend_url, headers=headers).text)
    friends_list = result.get("elements")
    friend_id = friends_list[0].get("uuid")
    send_url= "https://kapi.kakao.com/v1/api/talk/friends/message/default/send"

    kakao_send1={
        'receiver_uuids': '["{}"]'.format(friend_id),
        "template_object": json.dumps({
            "object_type":"text",
            "text":txt,
            "link":{
                "web_url":"zxz0608.cafe24.com",
            },
            "button_title": "바로 확인하기"
        })
    }
    response = requests.post(send_url, headers=headers, data=kakao_send1)
    response.status_code

#사용자 추가등록
def periodic_task():
    global lcdkey
    while True:
        if datarecv(table2='camera') == str(1):
            exec(open("/home/gt/Desktop/gttest/finally/my_face/face.py").read())
            
            current_time = time.strftime("%Y-%m-%d %H:%M:%S")
            sendphoto(osphoto="./doorLockManagement/1001/facePicture", file='face8.jpg')
            datasend(table1='camera', colom1='date', colom2='tp', data1=current_time, data2='0')
            lcdkey = 1
        time.sleep(1)

#스레드 시작
task_thread = threading.Thread(target=periodic_task)
task_thread.daemon = True
task_thread.start()

#GPIO 세팅
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)

GPIO.setup(pinRELAY, GPIO.OUT)

GPIO.setup(L1, GPIO.OUT)
GPIO.setup(L2, GPIO.OUT)
GPIO.setup(L3, GPIO.OUT)

GPIO.setup(C1, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)
GPIO.setup(C2, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)
GPIO.setup(C3, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)
GPIO.setup(C4, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)

def keypadCallback(channel):
    global keypadPressed
    if keypadPressed == -1:
        keypadPressed = channel

GPIO.add_event_detect(C1, GPIO.RISING, callback=keypadCallback)
GPIO.add_event_detect(C2, GPIO.RISING, callback=keypadCallback)
GPIO.add_event_detect(C3, GPIO.RISING, callback=keypadCallback)
GPIO.add_event_detect(C4, GPIO.RISING, callback=keypadCallback)

def setAllLines(state):
    GPIO.output(L1, state)
    GPIO.output(L2, state)
    GPIO.output(L3, state)

# 비밀번호 대조비교
def checkSpecialKeys():
    global current_time, input, count
    pressed = False

    GPIO.output(L3, GPIO.HIGH)

    if (GPIO.input(C4) == 1):
        lcd.lcd_clear()
        pressed = True

    GPIO.output(L3, GPIO.LOW)
    GPIO.output(L1, GPIO.HIGH)
    if (not pressed and GPIO.input(C4) == 1):

        if input == datarecv(table2='password') and datarecv(table2='error') != str('1'):
            current_time = time.strftime("%Y-%m-%d %H:%M:%S")
            lcd.lcd_display_string("Password correct", 1)
            time.sleep(1)
            lcd.lcd_clear()
            datasend(table1='textms', colom1='date', colom2='word', data1=current_time, data2='인증되었습니다.')
            count = -1

        elif input != datarecv(table2 = 'password') and datarecv(table2 = 'error') != str('1'):
            current_time = time.strftime("%Y-%m-%d %H:%M:%S")
            count += 1
            lcd.lcd_display_string("wrong password",1)
            time.sleep(1)
            lcd.lcd_clear()
            datasend(table1='textms', colom1='date', colom2='word', data1=current_time, data2='비밀번호가 틀렸습니다')

        pressed = True

    GPIO.output(L3, GPIO.LOW)

    if pressed:
        input = ""
    return pressed

#키패드 값 읽어오기
def readLine(line, characters):
    global input
    GPIO.output(line, GPIO.HIGH)
    if(GPIO.input(C1) == 1):
        input = input + characters[0]
    if(GPIO.input(C2) == 1):
        input = input + characters[1]
    if(GPIO.input(C3) == 1):
        input = input + characters[2]
    if(GPIO.input(C4) == 1):
        input = input + characters[3]
    GPIO.output(line, GPIO.LOW) 

while True:
    #키패드 배열 세팅밑 인식
    if keypadPressed != -1:
        setAllLines(GPIO.HIGH)
        if GPIO.input(keypadPressed) == 0:
            keypadPressed = -1
        else:
            time.sleep(0.1)
            
    else:
        if not checkSpecialKeys():
            readLine(L1, ["1","4","7","*"])
            readLine(L2, ["2","5","8","0"])
            readLine(L3, ["3","6","9","#"])
            time.sleep(0.1)
        else:
            time.sleep(0.1)

    if lcdkey == 1:
        lcd.lcd_clear()
        lcd.lcd_display_string("finish", 1)
        time.sleep(2)
        lcd.lcd_clear()
        lcdkey = 0

    if count == -1:
        lcd.lcd_clear()
        lcd.lcd_display_string("see the camera",1)

        reference_image_file1 = "/home/gt/Desktop/gttest/finally/my_face/face8.jpg"
        reference_image1 = cv2.imread(reference_image_file1, 1)
        ref_landmarks1, reference_face_descriptor1 = detect_face_embedding(reference_image1, landmark_predictor, face_rec_model)
        cap = cv2.VideoCapture(0, cv2.CAP_V4L)
        cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
        cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)
        ret, frame = cap.read()
        rcount+=1
        time.sleep(1)
        face_landmarks, face_descriptor = detect_face_embedding(frame, landmark_predictor, face_rec_model)

        if face_landmarks is not None:
            try:
                distance1 = np.linalg.norm(np.array(face_descriptor) - np.array(reference_face_descriptor1))
                rcount+=1
                time.sleep(1)

                #얼굴인증 맞을시 도어락 오픈
                if distance1 < 0.33:
                    lcd.lcd_clear()
                    lcd.lcd_display_string("open the door",1)
                    time.sleep(1)
                    GPIO.output(pinRELAY, GPIO.HIGH)
                    time.sleep(1)
                    GPIO.output(pinRELAY, GPIO.LOW)
                    count = 0
                    rcount = 0

                    #5초안에 얼굴인증 실패시 실행
                elif rcount > 5 and distance1 >= 0.33:
                    lcd.lcd_clear()
                    lcd.lcd_display_string("fail", 1)
                    time.sleep(1)
                    count = 6
                    rcount = 0

            except:
                lcd.lcd_clear
                lcd.lcd_display_string("reregister", 1)
                lcd.lcd_display_string("your face", 2)
                time.sleep(3)
        lcd.lcd_clear()
        cap.release()
        cv2.destroyAllWindows()

    #비밀번호 인증 3회 실패시 사진 촬영밑 전송
    if count == 3:
        current_time = time.strftime("%Y-%m-%d %H:%M:%S")
        timestr = time.strftime('%Y%m%d_%H%M%S')

        cap = cv2.VideoCapture(0, cv2.CAP_V4L)
        cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
        cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)
        filename = f'thief_{str(timestr)}.jpg'
        res, frame = cap.read()
        frame = cv2.flip(frame,1)
        cv2.imwrite(filename, frame) 
        cap.release()
        cv2.destroyAllWindows()
        
        datasend(table1='textms', colom1='date', colom2='word', data1=current_time, data2='사진을 촬영했습니다.')
        sendphoto(osphoto = "./Picture", file=filename)
        kakao("비밀번호 3회 오류")
        count = 4

    #비밀번호 인증 5회 실패시 반영구적 잠금
    if count == 6:
        lcd.lcd_display_string("activate number",1)

        current_time = time.strftime("%Y-%m-%d %H:%M:%S")
        datasend(table1='textms', colom1='date', colom2='word', data1=current_time, data2='비밀번호가 비활성화 되었습니다.')
        datasend(table1='error', colom1='date', colom2='errorcode', data1=current_time, data2='1')
        kakao("비밀번호 5회오류\n도어락 인증을 차단합니다")
        count = 7

    print(input)
    if rcount == 0:
        lcd.lcd_display_string("enter password", 1)
        lcd.lcd_display_string(input, 2)