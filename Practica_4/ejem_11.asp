<%@ Language="VBScript" %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo simple 11 </title>
</head>
<body bgcolor="#FFFFFF"> 
    <% If Request.Form.Count = 0 Then %>   
        <p align="center">
        <font color="#000080" size="5">ELIJA SU AUTOMOVIL</font>  
        </p>
        <form method="POST" action="ejem_11.asp">
        <table width="100%">
        <tr> <td> <font color="Blue" size="4"><u>Color</u></font>
        <input type="text" size="10" maxlength="256" name="Color"> </td>
        <td> <font color="Blue" size="4"><u>Motorización</u></font>
        <input type="checkbox" name="Diesel" value="SI">Diesel
        </td></tr>
        <tr><td><font color="Blue" size="4"><u>Marca</u></font>
        <select name="Marca" size="1">
            <option>AUDI</option>
            <option>BMW</option>
            <option>FIAT</option>
            <option>OPEL</option>
            <option>PEUGEOT</option>   
            <option>RENAULT</option>
            <option>SEAT</option>
            <option>TOYOTA</option>
        </select> </td>
        <td> <font color="Blue" size="4"><u>Gama</u></font>
        <input type="radio" name="Gama" value="GamaBaja">Baja
        <input type="radio" checked name="Gama" value="GamaMedia">Media   
        <input type="radio" name="Gama" value="GamaAlta">Alta </td></tr>
        <tr><td> <textarea name="Comentarios" rows="2" cols="32">Introduzca aquí sus comentarios</textarea> </td>
        <td><br><p align="center">
        <input type="submit" name="BotonEnvio" value="Envio">
        <input type="reset" name="BotonInicializar" value="Inicializar"></p>
        </td></tr></table>
        </form>
    <% Else %> 
        <h1>Datos introducidos por el usuario:</h1><br>  
        <% 
        For Each V_Entrada In Request.Form
            For Indice = 1 To Request.Form(V_Entrada).Count 
                Response.Write V_Entrada & ": " & Request.Form(V_Entrada)(Indice) & "<br>"
            Next
        Next 
        
        If (Request.Form("Marca") = "AUDI") AND (Request.Form("Diesel") = "SI") Then %>
            <br> Ha elegido usted un buen coche <br>
            <% If Request.Form("Gama") <> "GamaAlta" Then %>
                aunque no sea de la gama alta
            <% End If %>
        <% End If %>
    <% End If %>
</body>
</html>