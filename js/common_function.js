// 자바스크립트 공용 함수를 저장하는 js

// 새로고침으로 양식 재제출 하는 것을 방지하는 코드
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}

// 특정 값으로 submit 시키는 js - 스타일에 의해 수정이 모호해지면 사용
function submitForm(action) {
    const form = document.getElementById('myForm'); // 폼 지정
    const input = document.createElement('input');

    input.setAttribute('type', 'hidden');
    input.setAttribute('name', action); // 매개변수값이 submit됩니다.
    form.appendChild(input);
    form.submit();
}

// 지정 페이지로 이동하는 함수 - POST받아서 php처리하는게 아니면 이걸로 이동
// js로 이동하는 것이 좀 더 부드럽게 이동
function redirectToPage(data) {
    window.location.href = data + ".php";
}

// 사용자 변경하기 전에 재확인하는 함수 
function userImgSwitch() {
    confirm("인원을 수정하시겠습니까?") ? submitForm('YES') : alert('취소합니다.');
}

// menuicon을 누르면 menu가 flex되도록
window.onload = function() {
    const menuicon = document.getElementById('menuicon');
    const buttonContainer = document.getElementById('buttonContainer');
    menuicon.addEventListener('change', function() {
        if (menuicon.checked) {
            buttonContainer.style.display = 'flex';
        } else {
            buttonContainer.style.display = 'none';
        }
    });
};

const HIDDEN_CLASSNAME = "hidden";
// hidden class 제거
function removeHidden(element) {
    let form = document.getElementById(element);
    form.classList.remove(HIDDEN_CLASSNAME);
}
// hidden class 추가
function addHidden(element) {
    let form = document.getElementById(element);
    form.classList.add(HIDDEN_CLASSNAME);
}

// menu icon check 상태 해제
function revertMenuIcon() {
    const menuIcon = document.getElementById("menuicon");
    if (menuIcon.checked) {
        menuIcon.checked = false;
    }
}
