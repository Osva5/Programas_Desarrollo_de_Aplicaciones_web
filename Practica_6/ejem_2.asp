<%@ Language="VBScript" %>
<% Option Explicit %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo de bases de datos</title>
</head>
<body>
    <% 
    Dim Obj_Conn, Obj_RS, I
    Set Obj_Conn = Server.CreateObject("ADODB.Connection")
    Set Obj_RS = Server.CreateObject("ADODB.Recordset")
    
    Obj_Conn.Open "Alumnos"
    Obj_RS.Open "Datos_Alumnos", Obj_Conn, 3, 3
    
    If Obj_RS.EOF Then
        Response.Write "<CENTER><H1>NO EXISTEN REGISTROS</H1></CENTER>"
    Else
    %>
    <table border="1" align="center">
        <tr>
            <% For I = 0 To Obj_RS.Fields.Count - 1 %>
                <th><%= Obj_RS.Fields(I).Name %></th>
            <% Next %>
        </tr>
        
        <% Do While Not Obj_RS.EOF %>
        <tr>
            <% For I = 0 To Obj_RS.Fields.Count - 1 %>
                <td><%= Obj_RS.Fields(I).Value %></td>
            <% Next %>
        </tr>
        <% 
            Obj_RS.MoveNext
        Loop 
        %>
    </table>
    <% 
    End If 
    
    Obj_RS.Close
    Obj_Conn.Close
    Set Obj_RS = Nothing
    Set Obj_Conn = Nothing
    %>
</body>
</html>