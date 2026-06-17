<%@ Language="VBScript" %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo simple 12</title>
</head>
<body>
    <% If Request.QueryString = "" Then %>
        <form method="GET" action="ejem_12.asp">
            <font size="4">Seminarios del segundo trimestre</font><br><br>
            <select name="Seminarios" multiple size="9">
                <option>Tratamiento de señal</option>
                <option>Compiladores ascendentes</option>
                <option>Programacion orientada a objetos</option>
                <option>Diseño VLSI</option>
                <option>Ingles cientifico</option>
                <option>Nivel OSI</option>
                <option>Arquitectura paralelas</option>
                <option>Programacion concurrente</option>
                <option>Comercio electronico</option>
            </select><br><br>

            Número de seminarios realizados en el primer cuatrimestre <br><br>
            <input type="radio" checked name="NumSeminarios" value="tres">Tres
            <input type="radio" name="NumSeminarios" value="cuatro">Cuatro
            <input type="radio" name="NumSeminarios" value="cinco">Cinco
            <p align="center"><input type="submit" name="BotonEnviar" value="Enviar"></p>
        </form>
    <% Else %>
        <h1>Datos introducidos por el usuario:</h1><br>
        <% 
        For Each V_Entrada In Request.QueryString
            For Indice = 1 To Request.QueryString(V_Entrada).Count 
                Response.Write V_Entrada & " = " & Request.QueryString(V_Entrada)(Indice) & "<br>"
            Next
        Next 
        %>
        <br>
        <% If Request.QueryString("Seminarios").Count < 3 Then %>
            Es obligatorio matricularse en al menos tres seminarios
            <% If Request.QueryString("NumSeminarios") <> "tres" Then %>
                aunque usted haya cursado <%=Request.QueryString("NumSeminarios")%> en el primer cuatrimestre
            <% End If %>
        <% End If %>
    <% End If %>        
</body>
</html>