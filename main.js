function creaInputs(div){
    var newInput = document.createElement("input");      
    div.innerHTML +="<br>";
    newInput.type="file";
    newInput.name="files[]";       
    div.appendChild(newInput);
}

function main(){
    
    var nInputs;
    var divInputs = document.getElementsByTagName("div")[0];
    
    document.getElementById("agregar").addEventListener("click", evt =>{     
        if(nInputs<10){
            creaInputs(divInputs)
            nInputs++;
            localStorage.setItem('nInputs',nInputs);
        } else {
            alert("Número máximo de inputs alcanzado");
    } 
    })
    
    document.getElementById("borrar").addEventListener("click", evt =>{
        //var divInputs = document.getElementsByTagName("div")[0];
        if(nInputs > 1){
            for(let i = 0; i <= 1; i++){
                divInputs.removeChild(divInputs.lastChild);
            }
            nInputs--;
            localStorage.setItem('nInputs',nInputs);

        } else{
            alert("Número mínimo de inputs alcanzado");
        }              
    })
    
    window.addEventListener('load', evt=>{
        if(localStorage.getItem('nInputs')){
            nInputs = parseInt(localStorage.getItem('nInputs'));
            for(let i = 0 ; i < nInputs - 1 ; i++){
                creaInputs(divInputs);
            }
        } else{
            //resto 1 porque el submit es un imput también
            nInputs = document.getElementsByTagName("input").length - 1;
        }       
    })
}

main();
