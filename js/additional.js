const nationalNo = document.getElementById("nationalNo");
const taxNo = document.getElementById("taxNo");
const website = document.getElementById("website");
const email = document.getElementById("email");
const mobile = document.getElementById("mobile");
const address = document.getElementById("address");

const nextBtn = document.querySelector(".btn-next");

window.addEventListener("load", () => {

    const data = JSON.parse(localStorage.getItem("additionalData"));

    if (!data) return;

    nationalNo.value = data.nationalNo || "";
    taxNo.value = data.taxNo || "";
    website.value = data.website || "";
    email.value = data.email || "";
    mobile.value = data.mobile || "";
    address.value = data.address || "";

});
