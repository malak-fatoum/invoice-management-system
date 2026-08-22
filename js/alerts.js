function showSuccess(title, text, redirect = null) {

    Swal.fire({
        icon: "success",
        title: title,
        text: text,
        confirmButtonColor: "#E10000"
    }).then(() => {

        if (redirect) {

            window.location.href = redirect;

        }

    });

}

function showError(title, text) {

    Swal.fire({
        icon: "error",
        title: title,
        text: text,
        confirmButtonColor: "#E10000"
    });

}

function confirmDelete(url) {

    Swal.fire({

        title: "حذف الفاتورة",

        text: "هل أنت متأكد من حذف هذه الفاتورة؟ لا يمكن التراجع بعد الحذف.",

        icon: "warning",

        showCancelButton: true,

        confirmButtonColor: "#E10000",

        cancelButtonColor: "#6c757d",

        confirmButtonText: "نعم، احذفها",

        cancelButtonText: "إلغاء",

        reverseButtons: true

    }).then((result) => {

        if (result.isConfirmed) {

            window.location.href = url;

        }

    });

}

function confirmDelete(url) {

    Swal.fire({

        title: "حذف الفاتورة",

        text: "هل أنت متأكد من حذف هذه الفاتورة؟ لا يمكن التراجع بعد الحذف.",

        icon: "warning",

        showCancelButton: true,

        confirmButtonColor: "#E10000",

        cancelButtonColor: "#6c757d",

        confirmButtonText: "نعم، احذفها",

        cancelButtonText: "إلغاء",

        reverseButtons: true

    }).then((result) => {

        if (result.isConfirmed) {

            window.location.href = url;

        }

    });

}

function errorMessage(message){

    Swal.fire({

        icon:"error",

        title:"خطأ",

        text:message,

        confirmButtonColor:"#E10000"

    });

}

function successMessage(message, redirect = null){

    Swal.fire({
        icon: "success",
        title: "تم بنجاح",
        text: message,
        confirmButtonColor: "#E10000"
    }).then(() => {

        if (redirect) {
            window.location.href = redirect;
        }

    });

}