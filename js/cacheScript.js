$(document).ready(function () {
    const handleTargetImgUrl = () => {
        let targetImg = document.getElementById("targetImg");	// targetImg라는 아이디를 가진 이미지 가져옴
        const oldUrl = targetImg.src;	// 이미지 src 파싱
        const array = new Uint32Array(1);	// 32비트 정수 배열 생성
        const rndNumforCache = window.crypto.getRandomValues(array)[0];	// 강력한 난수를 생성하기 위함
        let paramsString = `?v=${rndNumforCache}`;	// src 뒤에 붙여 줄 문자열
        let newImgSrc = `${oldUrl}${paramsString}`;	// 새로운 src 생성
        targetImg.src = newImgSrc;	// 원래의 src와 교체
    }
});