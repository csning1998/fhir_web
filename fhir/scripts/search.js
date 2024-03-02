document.addEventListener("DOMContentLoaded", function () {
    var checkboxes = document.querySelectorAll('.select-checkbox');
    
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                // 获取选中行的 IC_number
                var icNumber = checkbox.getAttribute('data-ic-number');
                console.log('选中的 IC_number:', icNumber);
                // 这里可以执行其他操作，例如将选中的 IC_number 存储在数组中
            }
        });
    });
});
function redirectToChangePage() {
var selectedCheckbox = document.querySelector('.search-checkbox:checked');

if (selectedCheckbox) {
// 獲取選中行的 IC_number
var icNumber = selectedCheckbox.getAttribute('data-ic-number');
console.log('選中的 IC_number:', icNumber);

// 這裡可以執行其他操作，例如將選中的 IC_number 存儲在數組中

// 然後執行頁面跳轉
window.location.href = '/fhir/change.html';
} else {
alert('未勾選數據進行轉換操作。');
}
}

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
    xhr.open("GET", "/php/search.php?searchValue=" + searchValue, true);
    xhr.send();
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
xhr.open("GET", "/php/search.php?searchValue=" + searchValue, true);
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