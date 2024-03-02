src="https://code.jquery.com/jquery-3.6.4.min.js"

function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
  }

  // 获取 IC_number 参数的值
  var icNumberParam = getUrlParameter('IC_number');

  // 如果没有传入 IC_number 参数，则跳转回搜索页面
  if (!icNumberParam) {
    window.location.href = 'search.html';
  }

  // 使用 AJAX 请求数据
  $.ajax({
    url: 'view_drug.php?IC_number=' + icNumberParam,  // 将 IC_number 作为参数传递
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      // 成功取得数据后，将数据显示在 HTML 页面上
      document.getElementById('patient_name').innerText = data.medical_record.patient_name;
      document.getElementById('IC_number').innerText = data.medical_record.IC_number;
      document.getElementById('patient_id').innerText = data.medical_record.patient_id;
      document.getElementById('doctor_id').innerText = data.medical_record.doctor_id;
      document.getElementById('record_date').innerText = data.medical_record.record_date;
      document.getElementById('subject_description').innerText = data.medical_record.subject_description;
      document.getElementById('object_description').innerText = data.medical_record.object_description;
      document.getElementById('treatment_programs').innerText = data.medical_record.treatment_programs;
      document.getElementById('icd-10').innerText = data.icd_10.ICD_code + ": " + data.icd_10.ICD_name;

      // 显示藥物表格
      buildDrugTable(data.drug);
    },
    error: function () {
      console.error('无法取得医疗记录、ICD-10 和药物数据');
    }
  });

function buildDrugTable(drugData) {
  var table = document.getElementById('customers');
  var tbody = table.querySelector('tbody') || document.createElement('tbody');

  // 清空表格內容
  tbody.innerHTML = '';

  // 填充表格
  for (var j = 0; j < drugData.length; j++) {
      var drug = drugData[j];
      var drugRow = document.createElement('tr');

      // 按照特定順序顯示感興趣的屬性
      var columns = ['drug_id', 'english_name', 'chinese_name', 'drug_unit', 'drug_usage', 'take_date', 'drug_total'];

      for (var k = 0; k < columns.length; k++) {
          var prop = columns[k];
          var td = document.createElement('td');
          td.innerText = drug[prop];
          drugRow.appendChild(td);
      }

      tbody.appendChild(drugRow);
  }

  // 如果 tbody 不在表格中，則添加進去
  if (!table.querySelector('tbody')) {
      table.appendChild(tbody);
  }
}
