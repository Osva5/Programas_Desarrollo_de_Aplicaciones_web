<%@ Language="VBScript" %>
<% Option Explicit %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo de Paginación</title>
</head>
<body>
    <%
    Dim Obj_Conn, Obj_RS, Pagina, Cont
    
    Set Obj_Conn = Server.CreateObject("ADODB.Connection")
    Set Obj_RS = Server.CreateObject("ADODB.RecordSet")

    Obj_Conn.Open "DSN=Alumnos"
    ' CursorLocation 3 permite paginación (adUseClient)
    Obj_RS.CursorLocation = 3 
    Obj_RS.Open "Datos_Alumnos", Obj_Conn, 3, 3

    If Obj_RS.EOF Then
        Response.Write "<CENTER><H1>NO EXISTEN REGISTROS</H1></CENTER>"
    Else
        Obj_RS.PageSize = 5
        Pagina = Request.QueryString("Pagina")
        If Pagina = "" Then Pagina = 1 Else Pagina = CInt(Pagina)
        
        ' Asegurar que la página esté en rango
        If Pagina < 1 Then Pagina = 1
        If Pagina > Obj_RS.PageCount Then Pagina = Obj_RS.PageCount
    %>

    <TABLE BORDER="1" ALIGN="CENTER">
        <TR>
            <TD ALIGN="CENTER">
                <% If Pagina > 1 Then %>
                    <A HREF="ejem_6.asp?Pagina=<%=Pagina - 1%>">&lt;&lt; Anterior</A>
                <% End If %>
            </TD>
            <TD ALIGN="CENTER">Página <%=Pagina%> de <%=Obj_RS.PageCount%></TD>
            <TD ALIGN="CENTER">
                <% If Pagina < Obj_RS.PageCount Then %>
                    <A HREF="ejem_6.asp?Pagina=<%=Pagina + 1%>">Siguiente &gt;&gt;</A>
                <% End If %>
            </TD>
        </TR>
    </TABLE>

    <TABLE BORDER="1" ALIGN="CENTER">
        <TR>
            <TH>Nombre</TH>
            <TH>Apellidos</TH>
            <TH>D.N.I</TH>
        </TR>
        <%
        Obj_RS.AbsolutePage = Pagina
        Cont = 0
        Do While Not Obj_RS.EOF And Cont < Obj_RS.PageSize
        %>
        <TR>
            <TD><%=Obj_RS("Nombre")%></TD>
            <TD><%=Obj_RS("Apellidos")%></TD>
            <TD><%=Obj_RS("DNI")%></TD>
        </TR>
        <%
            Cont = Cont + 1
            Obj_RS.MoveNext
        Loop
        %>
    </TABLE>
    <%
    End If

    Obj_RS.Close
    Obj_Conn.Close
    Set Obj_RS = Nothing
    Set Obj_Conn = Nothing
    %>
</body>
</html>