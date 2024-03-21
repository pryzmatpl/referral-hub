var path = require("path"),
    express = require("express"),
    history = require('connect-history-api-fallback');

var DIST_DIR = path.join(__dirname, "dist"),
    PORT = 3000,
    app = express();

app.use(history());

//Serving the files on the dist folder
app.use(express.static(DIST_DIR));

//Send index.html when the user access the web
app.get("*", function (req, res) {
  res.sendFile(path.join(DIST_DIR, "index.html"));
});

app.listen(PORT)
console.log('Server started at port: ' + PORT)