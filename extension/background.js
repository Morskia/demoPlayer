// async function getCurrentTab() {/* ... */}
// let tab = await getCurrentTab();

// chrome.scripting.executeScript({
//     target: {tabId: tab.id},
//     files: ['content-script.js']
// });

// content-script.js
chrome.tabs.onUpdated.addListener( function (tabId, changeInfo, tab) {
    chrome.windows.update(chrome.windows.WINDOW_ID_CURRENT, { state: "fullscreen"});
})

// chrome.windows.update(chrome.windows.WINDOW_ID_CURRENT, { state: "fullscreen" })
