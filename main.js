const { app, BrowserWindow, Tray, Menu } = require("electron");

const path = require("path");

const url = require("url");

// init
let win;

function createWindow() {
  win = new BrowserWindow({
    width: 1600,
    height: 1200,
    icon: __dirname + "/app/assets/img/favicon.png",
  });
  win.loadURL(
    url.format({
      pathname: path.join(__dirname, "player.html"),
      protocol: "file",
      slashes: true,
    })
  );

  let tray = new Tray("/home/ali/projects/java/ePlayer/favicon.png");
  const contextMenu = Menu.buildFromTemplate([
    { label: "Item1", type: "radio" },
    { label: "Item2", type: "radio" },
    { label: "Item3", type: "radio", checked: true },
    { label: "Item4", type: "radio" },
  ]);
  tray.setToolTip("This is my application.");
  tray.setContextMenu(contextMenu);

  // open dev tools
  // win.webContents.openDevTools();
  win.on("closed", () => {
    win = null;
  });
}

// Create
app.on("ready", createWindow);

// Close
app.on("window-all-closed", () => {
  if (process.platform !== "darwin") {
    app.quit();
  }
});
