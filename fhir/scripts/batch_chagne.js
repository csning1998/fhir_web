function redirectToTargetPage() {
    window.location.href = '/fhir/search.html';
  }
  
function redirectToHomePage() {
    window.location.href = '/fhir/home.html';
  }
  
  const checkbox = document.getElementById('checkbox');

  checkbox.addEventListener('change', function() {
  if (checkbox.checked) {
    const parentNode = checkbox.parentNode;
    const firstChild = parentNode.firstChild;
    const value = firstChild.textContent;
  
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'batch_change.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('value=' + encodeURIComponent(value));
  
    xhr.onload = function() {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
  
        // 處理response
      } else {
        console.log('Error: ' + xhr.statusText);
      }
    };
  }
  });

  // 使用方法

fetch('./php/batch_change.php')
  .then(response => response.json())
  .then(data => {
    const fhirFormattedJSON = JSON.stringify(data, null, 2);
    document.getElementById('fhirData').innerHTML = '<pre>' + fhirFormattedJSON + '</pre>';
  }).catch(error => console.error('獲取 FHIR 數據時發生錯誤：', error));
