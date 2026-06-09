<style>
@font-face {
    font-family: 'archon_code_39_barcoderegular';
    src: url('fonts/archon_code_39_barcode-webfont.woff2') format('woff2'), url('fonts/archon_code_39_barcode-webfont.woff') format('woff');
    font-weight: normal; font-style: normal;
}
body{
    direction: rtl;
}
.invoice{
    width: 18cm;
    height: 12.5cm;
    border-width: 4px;
    border-style: solid;
    border-color: black;
    margin: auto;
}
.invoiceNo{
    font-family: 'archon_code_39_barcoderegular';
    font-size: x-large;
}
.invoiceTable{
    border-spacing: 0px;
}
.invoiceTable td{
    padding: 0px;
}
.seperator{
    width: 18cm;
    height: 1.5cm;
    border-style: none;
    margin: auto;
}
.seperator hr{
    width: 100%;
    border-top-width: 2px;
    border-top-style: dashed;
    border-top-color: black;
}
.logo{
    width: 2cm;
    height: 2cm;
    margin-right: 0px;
}
.logo img{
    width: 90%;
    margin: auto;
}
.title{
    width: 16cm;
    text-align: center;
}
.title-text{
    font-size: large;
    font-weight: bold;
}
.details{
    width: 100%;
    text-align: right;
    padding: 5px;
}
.details-text{
    font-size: small;
}
.details-title{
    font-size: medium;
    font-weight: bold;
}
.details-title-underlined{
    font-size: medium;
    text-decoration: underline;
    font-weight: bold;
}
.details-table{
    width: 70%;
    margin: auto;
}
.details-label{
    font-weight: bold;
    width: 70%;
}
.details-amount{
    width:30%;
}
.total-amount{
    border-top-style: solid;
    border-bottom-style: double;
    border-top-color: black;
    border-bottom-color: black;
}
.signatures-table{
    width: 100%;
}
.signatures{
    width: 33%;
    text-align: center;
}
.signatures-text{
    font-size: large;
    font-weight: bold;
}
.copy{
    background-image: url("img/bg.jpg");
    background-size: cover;
}
.footer-table{
    width: 100%;
}
.footer-comment{
    text-align: right;
}
.footer-date{
    text-align: left;
}
</style>