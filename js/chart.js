$(function() {
    var cpu = new SmoothieChart({millisPerPixel:50,grid:{fillStyle:'transparent',borderVisible:false,strokeStyle:'#bdc3c7'},maxValue:100,minValue:0});
    var ram = new SmoothieChart({millisPerPixel:50,grid:{fillStyle:'transparent',borderVisible:false,strokeStyle:'#bdc3c7'},maxValue:100,minValue:0});
    var bandwidth = new SmoothieChart({millisPerPixel:50,grid:{fillStyle:'transparent',borderVisible:false,strokeStyle:'#bdc3c7'},minValue:0});
    
    var cpudata = new TimeSeries();
    var ramdata = new TimeSeries();
    var swapdata = new TimeSeries();
    var netRx = new TimeSeries();
    var netTx = new TimeSeries();
    
    cpu.streamTo(document.getElementById("cpuusage"),1000);
    ram.streamTo(document.getElementById("ramusage"),1000);
    bandwidth.streamTo(document.getElementById("bandwidthusage"),1000);
    
    cpu.addTimeSeries(cpudata,{lineWidth:1.4,strokeStyle:'#d35400',fillStyle:'rgba(230,126,34,0.15)'});
    ram.addTimeSeries(ramdata,{lineWidth:1.4,strokeStyle:'#2980b9',fillStyle:'rgba(58,155,220,0.15)'});
    ram.addTimeSeries(swapdata,{lineWidth:1.4,strokeStyle:'#27ae60',fillStyle:'rgba(46,204,113,0.15)'});
    bandwidth.addTimeSeries(netRx,{lineWidth:1.4,strokeStyle:'#16a085',fillStyle:'rgba(26,188,156,0.15)'});
    bandwidth.addTimeSeries(netTx,{lineWidth:1.4,strokeStyle:'#8e44ad',fillStyle:'rgba(155,89,182,0.15)'});
    
    setInterval(function() {
        
        var formData = 'a=getUsage'; 
        $.ajax({
            url : "ajax.php",
            type: "GET",
            data : formData,
            success: function(data, textStatus, jqXHR)
            {
                parts = data.split(" ");
                cpudata.append(new Date().getTime(), parts[0]);
                ramdata.append(new Date().getTime(), parts[1]);
                swapdata.append(new Date().getTime(), parts[2]);
                netRx.append(new Date().getTime(), parts[3]);
                netTx.append(new Date().getTime(), parts[4]);
            }
        });
                
    }, 1000);
});
