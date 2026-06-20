<%@ Language="VBScript" %>
<% Option Explicit %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buscador de Alumnos</title>
</head>
<body>
    <%
    ' Si el formulario no ha sido enviado, mostramos el buscador
    If Request.Form("Nombre") = "" Then
    %>
        <FORM METHOD="Post" ACTION="ejem_4.asp">
            Nombre del alumno: <INPUT NAME="Nombre"> 
            <INPUT TYPE="Submit" VALUE="Enviar">
        </FORM> 
    <%
    Else
        ' Declaración de variables
        Dim Obj_Conn, Obj_RS, Nombre
        
        Nombre = Request.Form("Nombre")

        Set Obj_Conn = Server.CreateObject("ADODB.Connection")
        Set Obj_RS = Server.CreateObject("ADODB.Recordset")

        Obj_Conn.Open "Alumnos"
        ' Abrimos el recordset
        Obj_RS.Open "Datos_Alumnos", Obj_Conn, 3, 3
        
        ' Aplicamos el filtro
        Obj_RS.Filter = "Nombre='" & Nombre & "'"

        If Obj_RS.EOF Then
            Response.Write "<CENTER><H1>NO EXISTEN REGISTROS PARA: " & Nombre & "</H1></CENTER>"
        Else
    %>
            <TABLE BORDER="1" ALIGN="CENTER">
                <TR>
                    <TH>Nombre</TH>
                    <TH>Apellidos</TH>
                    <TH>D.N.I</TH>
                </TR> 
                <% Do While Not Obj_RS.EOF %>
                <TR>
                    <TD><%= Obj_RS("Nombre") %></TD>
                    <TD><%= Obj_RS("Apellidos") %></TD>
                    <TD><%= Obj_RS("DNI") %></TD>
                </TR> 
                <% 
                    Obj_RS.MoveNext
                Loop 
                %>
            </TABLE>
    <%
        End If

        ' Limpieza
        Obj_RS.Close
        Obj_Conn.Close
        Set Obj_RS = Nothing
        Set Obj_Conn = Nothing
    End If
    %>
</body>
</html>