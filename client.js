const form = document.getElementById("form");
/********************************************************************/
/********************************************************************/
/********************************************************************/
document.getElementById("submit_btn").addEventListener("mouseover", checkCity);
function checkCity() {
  document.getElementById("city").value = document
    .getElementById("city")
    .value.trim();
  if (document.getElementById("city").value.length === 0) {
    alert("Must have a valed field entered in for the city.");
    document.getElementById("city").focus();
  }
}

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

  fetch("http://localhost/de/api.php", {
    method: "post",
    body: JSON.stringify({ state, city })
  })
    .then(function(response) {
      return response.json();
    })
    .then(function(myJson) {
      clearZipList();
      const output = document.getElementById("zipCodeList");
      myJson.forEach((item, index) => {
        addToZipList(item);
      });
    });
}
