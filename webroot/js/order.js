/** property */
let element = null;
let blank = null;

let contents = document.querySelectorAll('.js-productOrder');
const root = document.getElementById('productOrderList');
let dropZone = document.querySelector('.js-dropZone');

/** func */
const init = () => {
  /** init */
  dropZone.removeEventListener('dragover', onDragOverDropTarget);
  dropZone.removeEventListener('drop', onDrop);

  blank.classList.add('js-dropZone');
  dropZone.classList.remove('js-dropZone');
  dropZone.classList.remove('is-active');
  dropZone = blank
  root.insertBefore(dropZone, root.lastChild);

  dropZone.addEventListener('dragover', onDragOverDropTarget);
  dropZone.addEventListener('drop', onDrop);
}

const getOrder = () => {
  const productOrderList = document.querySelectorAll('.js-productOrder');

  productOrderList.forEach(productOrder => {
    if (!productOrder.classList.contains('js-dropZone')) {
      const index = [].slice.call(productOrderList).indexOf(productOrder)
      productOrder.querySelector('input[name="order[]"]').setAttribute('value', index + 1)
    }
  })
}

const onDragStart = e => {
  element = e.target;
  blank = element.parentNode;
  element.style.opacity = 1;
}

const onDrag = e => element.style.opacity = 0;


const onDragEnd = e => {
  element.removeAttribute('style');
  dropZone.classList.remove('is-active');
}

const onDragOverDragTarget = (t, e) => {
  e.preventDefault();
  if (t.parentElement != element.parentElement) {
    root.insertBefore(dropZone, t.parentElement.nextElementSibling);
    dropZone.classList.add('is-active');
  }
}

const onDragOverDropTarget = e => e.preventDefault();

const onDrop = e => {
  e.preventDefault();
  element.parentNode.removeChild(element);
  dropZone.appendChild(element);

  init();

  getOrder();
}

/** actions */
getOrder();

contents.forEach(t => {
  t.addEventListener('dragstart', onDragStart);
  t.addEventListener('drag', onDrag);
  t.addEventListener('dragend', onDragEnd);
  t.addEventListener('dragover', e => onDragOverDragTarget(t, e));
})

dropZone.addEventListener('dragover', onDragOverDropTarget);
dropZone.addEventListener('drop', onDrop);