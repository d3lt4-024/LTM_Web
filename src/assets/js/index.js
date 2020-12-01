$("#resultInfo").hide();
$(document).ready(function () {
    baguetteBox.run(".gallery");
    loadStoredImageLinks();
    $("#showUploadDiv").click(function () {
        show_upload_form();
    });

    $("#showFetchDiv").click(function () {
        show_fetch_form();
    });

    $(".custom-file-input").on("change", function () {
        var fileName = $(this)
            .val()
            .split("\\")
            .pop();
        $(this)
            .siblings(".custom-file-label")
            .addClass("selected")
            .html(fileName);
    });
});

function show_upload_form() {
    $("#fetchDiv").hide();
    $("#uploadDiv").slideToggle("slow");
}

function show_fetch_form() {
    $("#uploadDiv").hide();
    $("#fetchDiv").slideToggle("slow");
}

function uploadImage(event) {
    event.preventDefault();
    var uploadForm = $("#uploadForm").get(0);
    $.ajax({
        type: "POST",
        url: "/controller/uploadImage.php",
        data: new FormData(uploadForm),
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            // console.log(response);
            var data = JSON.parse(response);
            if (data.status == "success") {
                $("#resultDiv").append(
                    '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                    '<p id="resultInfoContent">' +
                    "<strong>Upload Succeed!</strong> Your image link: " +
                    data.link +
                    "</p>" +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span> </button> </div>'
                );
                loadStoredImageLinks();
            } else if (data.status == "error") {
                $("#resultDiv").append(
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    '<p id="resultInfoContent">' +
                    "<strong>Upload Failed!</strong> " +
                    data.msg +
                    "</p>" +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span> </button> </div>'
                );
            }
        }
        // error: function (req, status, error) {
        // 	console.log(arguments);
        // }
    });
}

function fetchImage(event) {
    event.preventDefault();
    var fetchForm = $("#fetchForm");
    var clientUrl = fetchForm.find('input[name="clientUrl"]').val(),
        imageQuantity = fetchForm.find('input[name="imageQuantity"]').val();
    $.ajax({
        type: "POST",
        url: "/controller/fetchImage.php",
        data: {
            clientUrl: clientUrl,
            imageQuantity: imageQuantity
        },
        success: function (response) {
            // console.log(response);
            var data = JSON.parse(response);
            if (data.status == "success") {
                loadStoredImageLinks();
            }
        }
    });
}

function loadStoredImageLinks() {
    $.ajax({
        type: "POST",
        url: "/controller/storedImage.php",
        data: {
            action: "getStoredImages"
        },
        success: function (response) {
            // console.log(response);
            var data = JSON.parse(response);
            if (data.status == "success") {
                var imgLinks = data.msg;
                if (imgLinks == null) return;
                var listImgLinks = $("#listImgLinks");
                listImgLinks.empty();
                imgLinks.forEach(element => {
                    listImgLinks.append(
                        '<a href="' +
                        element +
                        '" class="lightbox center-cropped ml-3 mt-3">' +
                        '<img src="' +
                        element +
                        '" width="150" height="150"> </a>'
                    );
                });
            }
        }
    });
}
