
    <section class="row justify-content-center file-upload" id="file-upload">
        <div class="col-6 file-upload__wrapper">
            <h2>File upload</h2>
            <form enctype="multipart/form-data" action="ajax.php?upload" name="uploadForm" method="post" class="file-upload__form" id="upload-form">

                <div class="form-group" id="title-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" data-required="true"/>
                </div>

                <div class="form-group" id="description-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" class="form-control" data-required="true"/>
                </div>

                <div class="form-group file-area" id="image-group">
                    <label for="image">Images</label>
                    <input type="file" name="image[]" id="image" required="required" class="form-control-file" />
                    <div class="file-dummy">
                        <div class="success">Great, your files are selected. Keep on.</div>
                        <div class="default">Please select some files</div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block" name="file-upload">Upload images</button>
                </div>
            </form>
        </div>
    </section>