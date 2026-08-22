

// ======================================
// بيانات الفاتورة
// ======================================

const invoiceData = JSON.parse(localStorage.getItem("invoiceData"));

if(invoiceData){

    document.getElementById("invoiceNo").textContent =
    invoiceData.number;

    document.getElementById("invoiceNoAr").textContent =
    invoiceData.number;

    document.getElementById("invoiceDate").textContent =
    invoiceData.date;

    document.getElementById("invoiceDateAr").textContent =
    invoiceData.date;

    document.getElementById("currency").textContent =
    invoiceData.currency;

    document.getElementById("currencyAr").textContent =
    invoiceData.currency;

    document.getElementById("issuedBy").textContent =
    invoiceData.issuedBy;

    document.getElementById("issuedByAr").textContent =
    invoiceData.issuedBy;

    document.getElementById("invoiceStatement").textContent =
    invoiceData.statement;

}
// ======================================
// بيانات العميل
// ======================================

const customerData = JSON.parse(localStorage.getItem("customerData"));

if(customerData){

    document.getElementById("customerName").textContent =
    customerData.name;

    document.getElementById("customerNameAr").textContent =
    customerData.name;

    document.getElementById("customerAccount").textContent =
    customerData.account;

    document.getElementById("customerAccountAr").textContent =
    customerData.account;

}
// ======================================
// قراءة بنود الفاتورة
// ======================================

const invoiceItems = JSON.parse(localStorage.getItem("invoiceItems")) || [];

const invoiceTable = document.getElementById("invoiceItems");
const invoiceTotal = document.getElementById("invoiceTotal");

let grandTotal = 0;
// ======================================
// بيانات الحسابات البنكية
// ======================================

const bankData = JSON.parse(localStorage.getItem("bankData")) || {};

// ===== حساب الدولار USD =====

document.getElementById("usdBankName").textContent =
bankData.usdBankName || "";

document.getElementById("usdBranch").textContent =
bankData.usdBranch || "";

document.getElementById("usdIban").textContent =
bankData.usdIban || "";

document.getElementById("usdAccount").textContent =
bankData.usdAccount || "";

document.getElementById("usdCompany").textContent =
bankData.usdCompany || "";

// ===== حساب الدينار JOD =====

document.getElementById("jodBankName").textContent =
bankData.jodBankName || "";

document.getElementById("jodBranch").textContent =
bankData.jodBranch || "";

document.getElementById("jodIban").textContent =
bankData.jodIban || "";

document.getElementById("jodAccount").textContent =
bankData.jodAccount || "";

document.getElementById("jodCompany").textContent =
bankData.jodCompany || "";
// ===== CliQ =====

document.getElementById("cliqDetailsPreview").textContent =
bankData.cliqDetails || "";

document.getElementById("cliqIdPreview").textContent =
bankData.cliqId || "";

document.getElementById("cliqNamePreview").textContent =
bankData.cliqName || "";

document.getElementById("cliqBankPreview").textContent =
bankData.cliqBank || "";
// ======================================
// معلومات الشركة
// ======================================

const additionalData =
JSON.parse(localStorage.getItem("additionalData")) || {};

document.getElementById("nationalNo").textContent =
additionalData.nationalNo || "";

document.getElementById("nationalNoAr").textContent =
additionalData.nationalNo || "";

document.getElementById("taxNo").textContent =
additionalData.taxNo || "";

document.getElementById("taxNoAr").textContent =
additionalData.taxNo || "";

document.getElementById("website").textContent =
additionalData.website || "";

document.getElementById("websiteAr").textContent =
additionalData.website || "";

document.getElementById("email").textContent =
additionalData.email || "";

document.getElementById("emailAr").textContent =
additionalData.email || "";

document.getElementById("mobile").textContent =
additionalData.mobile || "";

document.getElementById("mobileAr").textContent =
additionalData.mobile || "";

document.getElementById("addressEn").textContent =
additionalData.address || "";

document.getElementById("addressAr").textContent =
additionalData.address || "";
// ======================================
// بيانات الشحنة
// ======================================

const shipmentData = JSON.parse(localStorage.getItem("shipmentData")) || {};

document.getElementById("masterBL").textContent =
shipmentData.masterBL || "";

document.getElementById("masterBLAr").textContent =
shipmentData.masterBL || "";

document.getElementById("houseBL").textContent =
shipmentData.houseBL || "";

document.getElementById("houseBLAr").textContent =
shipmentData.houseBL || "";

const customsText =
`${shipmentData.customsNumber || ""} - ${shipmentData.customsType || ""}`;

document.getElementById("customs").textContent = customsText;
document.getElementById("customsAr").textContent = customsText;

document.getElementById("shipper").textContent =
shipmentData.shipper || "";

document.getElementById("shipperAr").textContent =
shipmentData.shipper || "";

document.getElementById("consignee").textContent =
shipmentData.consignee || "";

document.getElementById("consigneeAr").textContent =
shipmentData.consignee || "";

document.getElementById("cargoDescription").textContent =
shipmentData.cargoDescription || "";

document.getElementById("cargoDescriptionAr").textContent =
shipmentData.cargoDescription || "";
// ======================
// ================
// عرض البنود
// ======================================

invoiceItems.forEach((item, index) => {

    grandTotal += Number(item.total);

    invoiceTable.innerHTML += `

        <tr>

            <td>${index + 1}</td>

            <td>${item.description}</td>

            <td>${item.quantity}</td>

            <td>${Number(item.unitPrice).toFixed(2)}</td>

            <td>${Number(item.total).toFixed(2)}</td>

        </tr>

    `;

});

// ======================================
// الإجمالي
// ======================================


invoiceTotal.textContent = grandTotal.toFixed(2);
// ===============================
// حفظ الفاتورة
// ===============================

const saveBtn = document.getElementById("saveInvoice");

saveBtn.addEventListener("click", () => {

    const invoiceData =
        JSON.parse(localStorage.getItem("invoiceData")) || {};

    const customerData =
        JSON.parse(localStorage.getItem("customerData")) || {};

    const savedInvoices =
        JSON.parse(localStorage.getItem("savedInvoices")) || [];

        console.log(invoiceData);
    savedInvoices.push({

    invoiceNumber: invoiceData.number || "",

    customer: customerData.name || "",

    date: invoiceData.date || "",

    currency: invoiceData.currency || "",

    total: grandTotal.toFixed(2),

    createdBy: invoiceData.issuedBy || "",

    status: "غير مدفوعة",

    invoiceData: invoiceData,

    customerData: customerData,

    shipmentData: JSON.parse(localStorage.getItem("shipmentData")) || {},

    bankData: JSON.parse(localStorage.getItem("bankData")) || {},

    additionalData: JSON.parse(localStorage.getItem("additionalData")) || {},

    invoiceItems: JSON.parse(localStorage.getItem("invoiceItems")) || []

});

    localStorage.setItem(
        "savedInvoices",
        JSON.stringify(savedInvoices)
    );

    // حذف البيانات المؤقتة

    localStorage.removeItem("invoiceData");
    localStorage.removeItem("customerData");
    localStorage.removeItem("shipmentData");
    localStorage.removeItem("bankData");
    localStorage.removeItem("additionalData");
    localStorage.removeItem("invoiceItems");

    alert("تم حفظ الفاتورة بنجاح");

    window.location.href = "savedInvoices.php";

});