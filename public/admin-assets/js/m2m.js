function fetch_m2m(sub_broker_id) {
    $.ajax({
        type: "GET",
        data: {broker_id: sub_broker_id},
        url: "",
        success: function (res) {
            console.log(res);
            var resp = JSON.parse(res);
            document.getElementById("net_pl").innerHTML=resp.net_profit_loss;
            document.getElementById("net_count").innerHTML=resp.net_count;
            document.getElementById("net_margin").innerHTML=resp.net_margin;
            var records = resp.records;
            var table = document.getElementById("m2m_table");
            for (var i = 0; i < records.length; i++) {
                var record = records[i];
                var broker_id = record.broker_id;
                if (document.getElementById("sub_" + broker_id)) {
                    var cell2 = document.getElementById("pl_" + broker_id);
                    var cell3 = document.getElementById("count_" + broker_id);
                    var cell4 = document.getElementById("margin_" + broker_id);
                } else {
                    var row = table.insertRow(1);
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);
                    var cell4 = row.insertCell(3);
                    cell1.id = "sub_" + broker_id;
                    cell2.id = "pl_" + i;
                    cell3.id = "count_" + i;
                    cell4.id = "margin_" + i;
                    if (parseInt(record.sub_brokers) > 0) {
                        cell1.innerHTML = '<a class="badge badge-pill badge-success" href="DashBoard.php?broker_id=' + broker_id + '">' + broker_id + ' : ' + record.broker_username;
                    } else {
                        cell1.innerHTML = '<a class="badge badge-pill badge-success" href="brokers-m2m.php?id==' + broker_id + '">' + broker_id + ' : ' + record.broker_username;
                    }
                }
                cell2.innerHTML = record.profit_loss;
                cell3.innerHTML = record.count;
                cell4.innerHTML = record.margin_used;
            }

            setTimeout(function () {
                fetch_m2m(sub_broker_id);
            }, 2000);
        }
    });
}