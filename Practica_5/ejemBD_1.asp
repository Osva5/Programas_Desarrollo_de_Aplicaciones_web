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
    Dim Obj_Conn, Obj_RS
    ' Crear objetos
    Set Obj_Conn = Server.CreateObject("ADODB.Connection")
    Set Obj_RS = Server.CreateObject("ADODB.Recordset")
    
    ' Abrir conexión (Asegúrate de que el DSN "Alumnos" esté configurado en el servidor)
    Obj_Conn.Open "Alumnos"
    
    ' Abrir tabla
    Obj_RS.Open "Datos_Alumnos", Obj_Conn, 3, 3
    
    If Obj_RS.EOF Then
        Response.Write "<CENTER><H1>NO EXISTE REGISTRO</H1></CENTER>"
    Else
    %>
    <table border="1" align="center">
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>DNI</th>
        </tr>
        <% Do While Not Obj_RS.EOF %>
        <tr>
            <td><%=Obj_RS("Nombre")%></td>
            <td><%=Obj_RS("Apellido")%></td>
            <td><%=Obj_RS("DNI")%></td>
        </tr>
        <% 
        Obj_RS.MoveNext 
        Loop 
        %>
    </table>
    <% 
    End If 
    
    ' Limpieza
    Obj_RS.Close
    Obj_Conn.Close
    Set Obj_RS = Nothing
    Set Obj_Conn = Nothing
    %>
</body>
</html>