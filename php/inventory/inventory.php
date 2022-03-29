<!DOCTYPE html>
<html>
<head>
<style>
#items {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#items td, #items th {
  border: 1px solid #ddd;
  padding: 8px;
}

#items tr:nth-child(even){background-color: #f2f2f2;}

#items tr:hover {background-color: coral;}

#items th {
  padding-top: 8px;
  padding-bottom: 8px;
  text-align: left;
  background-color: crimson;
  color: white;
}
</style>
</head>
<body>
<!--examples: Delete later-->
<table id="items">
  <tr>
    <th>Item</th>
    <th>UPC </th>
    <th>Qty</th>
    <th> Cost</th>
  </tr>
  <tr>
    <td>Oranges</td>
    <td>100001</td>
    <td>2</td>
    <td> $0.6 </td>
  </tr>
  <tr>
    <td>Apples</td>
    <td>100002</td>
    <td>3</td>
    <td> $0.67</td>
  </tr>
  <tr>
    <td>PineApples</td>
    <td>100003</td>
    <td>4</td>
    <td> $0.68</td>
  </tr>
</table>

</body>
</html>


