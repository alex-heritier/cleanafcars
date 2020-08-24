/* Load cars */
async function loadCars() {
  const response = await fetch('/server/get_cars.php').then((r)=>r.json());
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
  const response = await fetch('/server/delete_car.php', {
    method: 'post',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'id=' + carID
  }).then((r)=>r.json());
  console.log(response);

  loadCars();
}

/* Check each car entry for expiration then reload car listing */
async function checkExpiration() {
  const response = await fetch('/server/check_expiration.php', {
    method: 'post',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
  }).then((r)=>r.json());
  console.log(response);

  loadCars();
}

window.onload = function() {
  loadCars();

  document.querySelector('#check-expiration-btn').onclick = checkExpiration;
};
