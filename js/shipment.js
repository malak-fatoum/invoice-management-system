// ===============================
// عناصر الصفحة
// ===============================

const masterBL = document.getElementById("masterBL");
const houseBL = document.getElementById("houseBL");
const customsNumber = document.getElementById("customsNumber");
const customsType = document.getElementById("customsType");
const shipper = document.getElementById("shipper");
const consignee = document.getElementById("consignee");
const cargoDescription = document.getElementById("cargoDescription");

const nextBtn = document.querySelector(".btn-next");

// ===============================
// تحميل البيانات
// ===============================

window.addEventListener("load", () => {

    const data = JSON.parse(localStorage.getItem("shipmentData"));

    if (!data) return;

    masterBL.value = data.masterBL || "";
    houseBL.value = data.houseBL || "";
    customsNumber.value = data.customsNumber || "";
    customsType.value = data.customsType || "استيراد";
    shipper.value = data.shipper || "";
    consignee.value = data.consignee || "";
    cargoDescription.value = data.cargoDescription || "";

});

// ===============================
// حفظ البيانات والانتقال
// ===============================

nextBtn.addEventListener("click", (e) => {

    e.preventDefault();

    const shipmentData = {

        masterBL: masterBL.value.trim(),

        houseBL: houseBL.value.trim(),

        customsNumber: customsNumber.value.trim(),

        customsType: customsType.value,

        shipper: shipper.value.trim(),

        consignee: consignee.value.trim(),

        cargoDescription: cargoDescription.value.trim()

    };

    localStorage.setItem(
        "shipmentData",
        JSON.stringify(shipmentData)
    );

    window.location.href = "banks.php";

});