function main(){
    var divInputs = document.getElementsByTagName("div")[0];
    var nInputs = document.getElementsByTagName("input").length
    
    document.getElementById("agregar").addEventListener("click", evt =>{
        if(nInputs<=10){
            var newInput = document.createElement("input");      
            divInputs.innerHTML +="<br>";
            newInput.type="file";
            newInput.name="files[]";       
            divInputs.appendChild(newInput);
            nInputs++;
        } else {
            alert("Número máximo de inputs alcanzado");
    } 
    })
    
    document.getElementById("borrar").addEventListener("click", evt =>{
        if(nInputs > 2){
            for(let i = 0; i <= 1; i++){
                divInputs.removeChild(divInputs.lastChild);
            }
            nInputs--;
        } else{
            alert("Número mínimo de inputs alcanzado");
        }              
    })   
}

main();
