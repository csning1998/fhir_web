function redirectToTargetPage() {
    window.location.href = '/fhir/search.html';
  }
  
  function redirectToHomePage() {
    window.location.href = '/fhir/home.html';
  }
  
  // 使用方法
  
  fetch('/php/fetch_data.php')
    .then(response => response.json())
    .then(data => {
      const fhirFormattedJSON = JSON.stringify(data, null, 2);
      document.getElementById('fhirData').innerHTML = '<pre>' + fhirFormattedJSON + '</pre>';
    })
    .catch(error => console.error('獲取 FHIR 數據時發生錯誤：', error));
  