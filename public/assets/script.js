let button = document.getElementById('download_updates')
let circle = document.getElementById('circle')
let circle1 = document.getElementById('circle1')
let preloader_block = document.getElementById('preloader_for_1c')
let btnTxt = document.getElementById('btn-txt')

function clickBtn(){
    button.classList.add('clicked')
    circle.classList.add('rotation')
    preloader_block.classList.remove('d-none')
    circle1.classList.add('rotation')
}