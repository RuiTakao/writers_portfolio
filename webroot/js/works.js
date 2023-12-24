const url_path = document.getElementById('url-path');
const url_name = document.getElementById('url-name');
const func = () => {
    if (url_path.value == "") {
        url_name.setAttribute('disabled', 'disabled');
    } else {
        url_name.removeAttribute('disabled');
    }
}

func();

url_path.addEventListener('input', () => func());