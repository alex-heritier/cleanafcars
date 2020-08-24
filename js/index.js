/* Load cars */
async function loadCars() {
  const response = await fetch('/server/get_cars.php').then((r)=>r.json());
  console.log(response);

  const contentDom = document.querySelector('.content');
  // contentDom.innerHTML = ''; // Remove children
  response.forEach((car) => {
    const elem = document.createElement('div');
    elem.className = "content-item";
    elem.innerHTML = `
    <a href="/car.html?id=${car['id']}">
      <img src="${car['images'][0]}"/>
    </a>
    <div class="label">
      <h2>${car['model']}</h2>
      <p>\$${car['price']} | ${car['transmission']} | ${car['miles']} miles | ${car['year']}</p>
    </div>
    `;

    contentDom.appendChild(elem);
  });
}

window.onload = function() {
  loadCars();
};
