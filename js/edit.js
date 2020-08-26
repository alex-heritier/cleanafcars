/* Load car from server */
async function loadCar() {
  const carID = getCarID();

  const response = await fetch('/server/get_cars.php?id=' + carID).then((r)=>r.json());
  console.log(response);

  populateForm(response);
}

/* Populate form with car data */
function populateForm(car) {
  // Initialize form fields
  for (const key in car) {
    const dom = document.querySelector(`input[name=${key}]`);
    if (dom != null) dom.value = car[key];
  }

  // Create hidden inputs for images
  const root = document.querySelector('.hidden-images')
  root.innerHTML = '';
  for (const i in car['images']) {
    const elem = document.createElement('input');
    elem.name = `images[${i}]`;
    elem.type = 'hidden';
    elem.value = car['images'][i];
    root.appendChild(elem);
  }
}

/* Get car ID from url */
function getCarID() {
  const url = new URL(window.location.href);
  const carID = url.searchParams.get("id");
  return carID;
}

/* Scrape craigslist for posting info */
async function onPostingUrlChange(pasteEvent) {
  const pastedText = pasteEvent.clipboardData.getData("text");

  const response = await fetch('/server/scrape_cl.php?url=' + pastedText)
    .then((r)=>r.json())
    .catch((error)=>console.log(error));
  console.log(response);

  populateForm(response);
}

window.onload = function() {
  if (getCarID() != null) loadCar();

  document.querySelector('input[name=cl_link]').onpaste = onPostingUrlChange;
};
