const to_now = document.getElementById('to_now');
const end = document.getElementById('end');

if (to_now.checked) {
    end.value = '';
    end.style = 'background-color: #ccc;';
    end.setAttribute('disabled', 'disabled');
}

to_now.addEventListener('change', () => {
    if (to_now.checked) {
        end.style = 'background-color: #ccc;';
        end.setAttribute('disabled', 'disabled');
    } else {
        end.removeAttribute('disabled');
        end.removeAttribute('style');
    }
});