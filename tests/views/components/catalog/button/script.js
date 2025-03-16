let elements = document.querySelectorAll('.js-superbtn-click');

for (let elem of elements) {
    console.log(elem);
    elem.onclick = function() {
        alert('Click button ' + elem.dataset.id + '!');
    };
}