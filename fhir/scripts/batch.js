function redirectToChangePage() {
  window.location.href = '/fhir/batch_change.html';
}

document.addEventListener("DOMContentLoaded", function () {
  document.querySelector(".search-btn").addEventListener("click", function () {
    var searchValue = document.getElementById("search").value;

    // 使用 Ajax 發送請求到後端 PHP 腳本
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        // 處理後端返回的資料
        updateTable(xhr.responseText);

        // 使用 pushState 存储搜索信息
        window.history.pushState({ searchValue: searchValue }, document.title, '?search=' + searchValue);
      }
    };
    xhr.open("GET", "./php/batch.php?searchValue=" + searchValue, true);
    xhr.send();
  });
});

window.addEventListener('popstate', function (event) {
if (event.state) {
  var searchValue = event.state.searchValue;

  // 使用 Ajax 發送請求到後端 PHP 腳本
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      // 處理後端返回的資料
      updateTable(xhr.responseText);
    }
  };
  xhr.open("GET", "./php/batch.php?searchValue=" + searchValue, true);
  xhr.send();
}
});


function updateTable(response) {
document.getElementById("customers").innerHTML = response;

// 获取当前的搜索信息
var searchValue = document.getElementById("search").value;

// 使用 pushState 存储搜索信息
window.history.pushState({ searchValue: searchValue }, document.title, '?search=' + searchValue);
}
