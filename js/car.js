/* Get car ID from url */
function getCarID() {
  const url = new URL(window.location.href);
  const carID = url.searchParams.get("id");
  // console.log(carID);
  return carID;
}

/* Load car from server */
async function loadCar() {
  const carID = getCarID();

  const response = await fetch('/server/get_cars.php?id=' + carID).then((r)=>r.json());
  console.log(response);

  displayCar(response);
}

/* Display car in DOM */
function displayCar(car) {
  const content = document.querySelector('.content');
  content.innerHTML = `
  <div class="gallery">
    <img class="banner" src="${car['images'][0]}"/>
  </div>

  <div class="info">
    <h2>${car['model']}</h2>
    <p class="price">\$${car['price']}</p>
    <p class="description">${car['description'] || ""}</p>


    <div class="spec">
      <p>${car['transmission']}</p>
      <p>${car['miles']} miles</p>
    </div>

    <a href="${car['cl_link']}">View on Craigslist</a>
  </div>
  `;

  document.querySelector('title').innerText = `${car["year"]} ${car["model"]}`;
}

window.onload = function() {
  loadCar();
}
