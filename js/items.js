const description = document.getElementById("description");
const qty = document.getElementById("qty");
const price = document.getElementById("price");
const total = document.getElementById("total");

const addItem = document.getElementById("addItem");
const itemsBody = document.getElementById("itemsBody");
const grandTotal = document.getElementById("grandTotal");

let items = [];

function calculateTotal() {

    const quantity = parseFloat(qty.value) || 0;
    const unitPrice = parseFloat(price.value) || 0;

    total.value = (quantity * unitPrice).toFixed(2);

}

qty.addEventListener("input", calculateTotal);
price.addEventListener("input", calculateTotal);

qty.addEventListener("input", () => {

    if (qty.value < 1) qty.value = 1;

    calculateTotal();

});

price.addEventListener("input", () => {

    if (price.value < 0) price.value = 0;

    calculateTotal();

});

function saveSession() {

    fetch("save_items_session.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/json"
        },

        body: JSON.stringify(items)

    });

}

function renderItems() {

    itemsBody.innerHTML = "";

    let grand = 0;

    items.forEach((item, index) => {

        grand += Number(item.total);

        itemsBody.innerHTML += `

        <tr>

            <td>${index + 1}</td>

            <td>${item.description}</td>

            <td>${item.quantity}</td>

            <td>${item.unit_price}</td>

            <td>${item.total}</td>

            <td>

                <button
                    class="deleteBtn"
                    data-index="${index}">

                    حذف

                </button>

            </td>

        </tr>

        `;

    });

    grandTotal.value = grand.toFixed(2);

    saveSession();

}

addItem.addEventListener("click", () => {

    if (description.value.trim() == "") {

        alert("أدخل البيان");

        return;

    }

    items.push({

        description: description.value.trim(),

        quantity: qty.value,

        unit_price: price.value,

        total: total.value

    });

    description.value = "";
    qty.value = 1;
    price.value = "";
    total.value = "";

    description.focus();

    renderItems();

});

document.addEventListener("click", (e) => {

    if (!e.target.classList.contains("deleteBtn")) return;

    const index = e.target.dataset.index;

    items.splice(index, 1);

    renderItems();

});

window.addEventListener("load", () => {

    fetch("get_items_session.php")

    .then(response => response.json())

    .then(data => {

        if (Array.isArray(data)) {

            items = data;

            renderItems();

        }

    });

});