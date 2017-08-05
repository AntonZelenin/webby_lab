
<html>
    <head>
        <title>Movies</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

        <link rel="stylesheet" type="text/css" href="/css/style.css" />
        <script src="/js/jquery-3.2.1.js"></script>

        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    </head>

    <body>
        <div class="sidebar w3-sidebar">
            <form action="/scripts/addMovie.php" method="post" >
                <div class="add-movie">
                    Movie Title:<br />
                    <input type="text" class="textbox" name="name" placeholder="e.g. Matrix" required /><br />
                    Release Year:<br />
                    <input type="number" min='1900' class="textbox" max='2017' name="year" placeholder="e.g. 1999" required /><br />
                    Format: <br />
                    <input type="text" name="format" class="textbox" placeholder="e.g. DVD" required /><br />
                    Stars:<br />
                    <input type="text" name="actors" placeholder="e.g. Keanu Reeves, Laurence Fishburne" required /><br />
                </div>

                <input type="Submit" class="button" value="Add Movie" />
            </form>


            <div>
                <div class="search">
                    Search by movie name:<br />
                    <input type="text" name='name' placeholder="e.g. Jaws" required />
                </div>
                <input type="button" class="button" value="Search" onclick="submitForm(this.parentElement)" />
            </div>

            <div>
                <div class="search">
                    Search by actor name:<br />
                    <input type="text" name='actor' placeholder="e.g. Robert Shaw" required />
                </div>
                <input type="submit" class="button" value="Search" onclick="submitForm(this.parentElement)" />
            </div>

            <div>
                Select file to upload:
                <input type="file" name="file" id="file" required />
                <input type="submit" class="button" value="Upload" name="submit" />
            </div>

            <input type="button" class="button" value="Show all" onclick="reset()" />
            <input type="checkbox" id="order" onclick="reload()" /> Order by name
        </div>

        <div class="main" id="main"></div>

        <template id="movie-template">
            <div class='movie'>
                <span id='movie-name-year' onclick='showInfo(this.parentElement)'>$name ($year)</span>
                <div class='del' id='delete' onclick='del(this.parentElement)'>&times;</div>
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
        </template>

        <script src="js/common.js"></script>

     </body>

</html>
