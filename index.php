<html>
    <head>
        <title>Movies</title>
        <meta charset="utf-8">
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

        <link rel="stylesheet" type="text/css" href="/css/style.css" />
        <script src="/js/jquery-3.2.1.js"></script>

        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    </head>

    <body>
        <div class="wrapper">
            <div class="sidebar">
                <form action="/scripts/addMovie.php" method="post" >
                    <div class="form-wrapper">
                        <input type="text" name="name" placeholder="Movie Title" required />

                        <div class="year-format">
                            <input type="number" class="year" min='1900' max='2017' name="year" placeholder="Release Year" required />
                            <input type="text" name="format" placeholder="Format" required />
                        </div>

                        <input type="text" name="actors" placeholder="Stars (e.g. Keanu Reeves)" required />
                        <input type="Submit" class="submit-button" value="Add Movie" />
                    </div>

                </form>

                <div class="form-wrapper">
                    <input type="text" name='name' placeholder="Search by name" required />
                    <input type="button" class="submit-button" value="Search" onclick="submitForm(this.parentElement)" />
                </div>

                <div class="form-wrapper">
                    <input type="text" name='actor' placeholder="Search by actor" required />
                    <input type="submit" value="Search" onclick="submitForm(this.parentElement)" />
                </div>

                <div class="form-wrapper flex-row">
                    <input type="button" value="Reset" onclick="reset()" />
                    <input type="button" class="order" id="order" value="Order by name" onclick="order_list()">
                </div>

                <form action="/scripts/upload.php" class="upload" method="post" enctype="multipart/form-data">
                    <label>Select file to upload:</label>
                    <input type="file" name="file" style="display:none;" id="file" required />
                    <div class="browse">
                        <input type="button" value="Browse" id="uploadTrigger" />
                        <label id="browse-label">No file selected</label>
                    </div>
                    <!-- <input type="button" id="uploadTrigger">Browse</div> -->
                    <input type="submit" value="Upload" name="submit" />
                </form>
            </div>

            <div class="main" id="main"></div>
        </div>

        <template id="movie-template">
            <div class="movie-wrapper">
                <div class='movie'>
                    <span id='movie-name-year' onclick='showInfo(this.parentElement)'>$name ($year)</span>
                    <div class='del' id='delete' onclick='del(this.parentElement)' title="Delete">&times;</div>
                </div>

                <div class='modal'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <span class='close'>&times;</span>
                            <h2 id="movie-name"></h2>
                        </div>

                        <div class='modal-body'>
                            <p id="movie-id"></p>
                            <p id="release-year"></p>
                            <p id="format"></p>
                            <p id="actors"></p>
                        </div>

                    </div>
                </div>
            </div>
        </template>

        <script src="js/common.js"></script>

     </body>

</html>
