const form = document.getElementById("form");
/********************************************************************/
/********************************************************************/
/********************************************************************/
const addToZipList = zipcode => {
  const node = document.createElement("div");
  const textnode = document.createTextNode(zipcode);
  node.appendChild(textnode);
  document.getElementById("zipCodeList").appendChild(node);
};

const clearZipList = () => {
  try {
    const list = document.getElementById("zipCodeList");
    while (list.childNodes[0]) list.removeChild(list.childNodes[0]);
  } catch (e) {}
};
/********************************************************************/
/********************************************************************/
/********************************************************************/

const state = document.getElementById("state").value;
form.addEventListener("submit", submit);

function submit(e) {
  e.preventDefault();
  const city = document.getElementById("city").value;
  const state = document.getElementById("state").value;
  //************************************************
  //   const myJson = ["62454", "06535", "39635", "00986"];
  //   clearZipList();
  //   const output = document.getElementById("zipCodeList");
  //   myJson.forEach((item, index) => {
  //     addToZipList(item);
  //   });
  //************************************************

  fetch("http://localhost/de/api.php", {
    method: "post",
    body: JSON.stringify({ state, city })
  })
    .then(function(response) {
      return response.json();
    })
    .then(function(myJson) {
      //  output.innerHTML = JSON.stringify(myJson);

      clearZipList();
      const output = document.getElementById("zipCodeList");
      myJson.forEach((item, index) => {
        addToZipList(item);
      });
    });
}
