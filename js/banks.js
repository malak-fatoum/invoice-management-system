// ===============================
// زر التالي
// ===============================

const nextBtn = document.querySelector(".btn-next");

// ===============================
// تحميل البيانات
// ===============================

window.addEventListener("load", () => {

    const data = JSON.parse(localStorage.getItem("bankData"));

    if (!data) return;

    // ===== حساب الدولار =====

    document.getElementById("usdBankName").value = data.usdBankName || "";
    document.getElementById("usdBranch").value = data.usdBranch || "";
    document.getElementById("usdIban").value = data.usdIban || "";
    document.getElementById("usdAccount").value = data.usdAccount || "";
    document.getElementById("usdCompany").value = data.usdCompany || "";

    // ===== حساب الدينار =====

    document.getElementById("jodBankName").value = data.jodBankName || "";
    document.getElementById("jodBranch").value = data.jodBranch || "";
    document.getElementById("jodIban").value = data.jodIban || "";
    document.getElementById("jodAccount").value = data.jodAccount || "";
    document.getElementById("jodCompany").value = data.jodCompany || "";

    // ===== بيانات CliQ =====

    document.getElementById("cliqDetails").value = data.cliqDetails || "";
    document.getElementById("cliqId").value = data.cliqId || "";
    document.getElementById("cliqName").value = data.cliqName || "";
    document.getElementById("cliqBank").value = data.cliqBank || "";

});

// ===============================
// حفظ البيانات والانتقال
// ===============================

nextBtn.addEventListener("click", (e) => {

    e.preventDefault();

    const bankData = {

        // ===== USD =====

        usdBankName: document.getElementById("usdBankName").value.trim(),
        usdBranch: document.getElementById("usdBranch").value.trim(),
        usdIban: document.getElementById("usdIban").value.trim(),
        usdAccount: document.getElementById("usdAccount").value.trim(),
        usdCompany: document.getElementById("usdCompany").value.trim(),

        // ===== JOD =====

        jodBankName: document.getElementById("jodBankName").value.trim(),
        jodBranch: document.getElementById("jodBranch").value.trim(),
        jodIban: document.getElementById("jodIban").value.trim(),
        jodAccount: document.getElementById("jodAccount").value.trim(),
        jodCompany: document.getElementById("jodCompany").value.trim(),

        // ===== CliQ =====

        cliqDetails: document.getElementById("cliqDetails").value.trim(),
        cliqId: document.getElementById("cliqId").value.trim(),
        cliqName: document.getElementById("cliqName").value.trim(),
        cliqBank: document.getElementById("cliqBank").value.trim()

    };

    localStorage.setItem(
        "bankData",
        JSON.stringify(bankData)
    );

    window.location.href = "items.php";

});