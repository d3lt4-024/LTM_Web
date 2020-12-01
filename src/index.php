<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    session_regenerate_id();
    $_SESSION['loggedin'] = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <title>Ph-D</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.0/baguetteBox.css"/>
    <style type="text/css">
        .add-image {
            background-color: #f2fff9;
            border: 5px dashed #ecf0f1;
            border-radius: 5px;
            padding: 20px;
        }

        .uploadDiv {
            text-align: center;
        }

        .center-cropped {
            object-fit: none;
            /* Do not scale the image */
            object-position: center;
            /* Center the image within the element */
            height: 100px;
            width: 100px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.0/baguetteBox.js"
            type="text/javascript"></script>
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <!-- Brand -->
    <a class="navbar-brand" href="index.php">Ph-D</a>
    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
    </div>
</nav>
<main role="main" class="flex-shrink-0" style="padding-top: 25px">
    <div class="container">
        <h1 class="mt-5 text-center" style="color:#ff9249">
            <strong>Image Gallery</strong>
        </h1>
        <hr/>
        <div class="add-image">
            <div>
                <button class="btn btn-md btn-primary mb-3" id="showUploadDiv">
                    <i class="fas fa-upload"></i> Upload From Computer
                </button>
                <button class="btn btn-md btn-primary mb-3 ml-4" id="showFetchDiv">
                    <i class="fas fa-cloud-upload-alt"></i> Fetch From Url
                </button>
            </div>
            <div id="uploadDiv" style="display:none">
                <div class="card">
                    <h6 class="card-header">Notes</h6>
                    <div class="card-body">
                        <h5 class="card-title">Basic Feature</h5>
                        <div class="card-text">
                            <ul>
                                <li>Upload image from your computer.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <br>
                <form id="uploadForm" method="POST" enctype="multipart/form-data">
                    <div class="form-group custom-file">
                        <input type="file" class="custom-file-input form-control" name="clientImage"/>
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                    <br><br>
                    <div class="form-group text-right">
                        <button class="btn btn-md btn-success" id="uploadBtn" name="uploadBtn" value="1"
                                onclick="uploadImage(event)">
                            Upload
                        </button>
                    </div>
                </form>
                <div id="resultDiv"></div>
            </div>
            <div id="fetchDiv" style="display:none">
                <div class="card">
                    <h6 class="card-header">Notes</h6>
                    <div class="card-body">
                        <h5 class="card-title">"PhD" Feature</h5>
                        <div class="card-text">
                            <ul>
                                <li>Parse image contents from your url.</li>
                                <li>Allow only image with min 100*100 dimension.</li>
                                <li>Image are sorted by size, biggest one first.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <br>
                <form id="fetchForm">
                    <div class="form-group row">
                        <label for="FormGroupInput"
                               class="col-md-1 col-form-label col-form-label-md text-center">URL</label>
                        <div class="col-md-9">
                            <input type="url" class="form-control form-control-md" name="clientUrl"
                                   placeholder="http://" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control form-control-md text-center" name="imageQuantity"
                                   value="1" min="1">
                        </div>
                    </div>
                    <div class="text-right">
                        <button class="btn btn-md btn-success" id="fetchBtn" onclick="fetchImage(event)">
                            Fetch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    <div class="load-image row justify-content-center" id="loadImageDiv">
        <div id="listImgLinks" class="gallery col-md-10"></div>
    </div>
</main>
<script src="/assets/js/index.js" type="text/javascript"></script>
</body>

</html>