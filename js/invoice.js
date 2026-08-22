const invoiceNumber = document.getElementById("invoiceNumber");
const invoiceDate = document.getElementById("invoiceDate");
const currency = document.getElementById("currency");
const issuedBy = document.getElementById("issuedBy");
const invoiceStatement = document.getElementById("invoiceStatement");

const nextBtn = document.querySelector(".btn-next");

window.addEventListener("load", () => {

    const data = JSON.parse(localStorage.getItem("invoiceData"));

    if (!data) return;

    invoiceNumber.value = data.number || "";
    invoiceDate.value = data.date || "";
    currency.value = data.currency || "JOD";
    issuedBy.value = data.issuedBy || "";
    invoiceStatement.value = data.statement || "";

});