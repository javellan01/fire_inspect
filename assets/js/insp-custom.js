var opt_map = new Map([
    [".PR",0],
    [".A1",1],
    [".BC",2],
    [".D1",3],
    [".D2",4],
    [".DE1",5],
    [".GA",6],
    [".GB",7],
    [".OC",8],
    [".R1",9],
    [".S1",10],
    [".SX1",11],
    [".X1",12],
    [".PD",13],
    [".CUSTOM",14]
    ]);
var selected = '';


function setSelected(element,string){
    if(selected){
         $(selected).removeClass("selected");
    }
    selected = string;
    $(element).addClass("selected");
}

$(function(){



$('select#selIndice').on('change', function () {
    let string = ".";
    string = string.concat(this.options[this.selectedIndex].text);
    let el = $(string);
    setSelected(el,string);
}); 


$('input#custom').on('keyup', function () {
    document.querySelector('select#selIndice').options[14].selected = true;
    let value_c = this.value;
    document.querySelector('select#selIndice').options[14].value = value_c;
});

$('.card').on('click', function() {
    let string = ".";
    string = string.concat(this.classList[1]);
    setSelected(this,string);
    let index = opt_map.get(string);
    document.querySelector('select#selIndice').options[index].selected = true;
    if( index == 14){
        let value = $('input#custom').val();
        document.querySelector('select#selIndice').options[index].value = value;
    }
    
});

});
