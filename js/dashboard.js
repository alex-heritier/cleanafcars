/* Load cars */
async function loadCars() {
  const response = await fetch('/server/req_get_cars.php').then((r)=>r.json());
  console.log(response);

  const listingDom = document.querySelector('.listing');
  listingDom.innerHTML = ''; // Remove children
  response.forEach((car) => {
    const elem = document.createElement('div');
    elem.className = "listing-item";
    elem.innerHTML = `
    <a href="/car.php?id=${car['id']}">
      <img src="${(car['images'] || [''])[0]}" />
    </a>
    <div class="info">
      <span class="model">${car['model']}</span>
      <span class="price">\$${car['price']}</span>
    </div>
    <a class="edit-btn" href="/admin/edit.html?id=${car['id']}">Edit</a>
    <button class="delete-btn" data-car-id="${car['id']}">Delete</button>
    `;

    listingDom.appendChild(elem);
  });

  document.querySelectorAll('.delete-btn').forEach((btn) => {
    btn.onclick = () => deleteCar(btn.getAttribute('data-car-id'));
  });
}

/* Delete a car */
async function deleteCar(carID) {
  const response = await fetch('/server/req_delete_car.php', {
    method: 'post',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'id=' + carID,
  }).then((r)=>r.json());
  console.log(response);

  loadCars();
}

/* Check each car entry for expiration then reload car listing */
async function checkExpiration() {
  const response = await fetch('/server/req_check_expiration.php', {
    method: 'post',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
  }).then((r)=>r.json());
  console.log(response);

  loadCars();
}

/* Attempt to add car by craigslist URL */
async function onQuickAdd(pasteEvent) {
  const pastedText = pasteEvent.clipboardData.getData("text");
  const response = await fetch(
    '/server/req_quick_add.php',
    {
      method: 'post',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'url=' + pastedText,
    },
  )
    .then((r)=>r.json())
    .then((_)=> loadCars())
    .catch((error)=>console.log(error));
  console.log(response);
}

window.onload = function() {
  loadCars();

  document.querySelector('#check-expiration-btn').onclick = checkExpiration;

  document.querySelector('input[name=quick_add]').onpaste = onQuickAdd;
};
