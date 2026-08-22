// ================= العناصر =================

const fullName = document.getElementById("fullName");
const username = document.getElementById("username");
const email = document.getElementById("email");
const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirmPassword");
const role = document.getElementById("role");
const status = document.getElementById("status");

const saveUser = document.getElementById("saveUser");
const usersBody = document.getElementById("usersBody");

let userNumber = 1;

// ================= إضافة مستخدم =================

saveUser.addEventListener("click", function () {

    if (
        fullName.value.trim() === "" ||
        username.value.trim() === "" ||
        password.value === ""
    ) {

        alert("يرجى تعبئة جميع الحقول المطلوبة");

        return;

    }

    if (password.value !== confirmPassword.value) {

        alert("كلمة المرور غير متطابقة");

        return;

    }

    // منع تكرار اسم المستخدم

    const usernames = document.querySelectorAll(".username");

    for (let user of usernames) {

        if (user.textContent === username.value.trim()) {

            alert("اسم المستخدم مستخدم مسبقاً");

            return;

        }

    }

    const row = document.createElement("tr");

    row.innerHTML = `

        <td>${userNumber}</td>

        <td>${fullName.value}</td>

        <td class="username">${username.value}</td>

        <td>${email.value}</td>

        <td>${role.options[role.selectedIndex].text}</td>

        <td>
            <span class="status ${status.value}">
                ${status.value === "active" ? "فعال" : "موقوف"}
            </span>
        </td>

        <td>
            <button class="edit-btn">✏️</button>
        </td>

        <td>
            <button class="delete-btn">🗑</button>
        </td>

    `;

    usersBody.appendChild(row);

    userNumber++;

    fullName.value = "";
    username.value = "";
    email.value = "";
    password.value = "";
    confirmPassword.value = "";

    role.selectedIndex = 0;
    status.selectedIndex = 0;

});
// ================= حذف مستخدم =================

document.addEventListener("click", function (e) {

    if (!e.target.classList.contains("delete-btn")) return;

    if (!confirm("هل تريد حذف هذا المستخدم؟")) return;

    const row = e.target.closest("tr");

    row.remove();

    const rows = usersBody.querySelectorAll("tr");

    userNumber = 1;

    rows.forEach(function (row) {

        row.cells[0].textContent = userNumber++;

    });

});
// ================= تعديل مستخدم =================

document.addEventListener("click", function (e) {

    if (!e.target.classList.contains("edit-btn")) return;

    const row = e.target.closest("tr");

    fullName.value = row.cells[1].textContent;

    username.value = row.cells[2].textContent;

    email.value = row.cells[3].textContent;

    role.value =
        row.cells[4].textContent === "مدير النظام"
            ? "admin"
            : row.cells[4].textContent === "محاسب"
            ? "accountant"
            : "viewer";

    status.value =
        row.querySelector(".status").textContent.trim() === "فعال"
            ? "active"
            : "inactive";

    row.remove();

    const rows = usersBody.querySelectorAll("tr");

    userNumber = 1;

    rows.forEach(function (row) {

        row.cells[0].textContent = userNumber++;

    });

});