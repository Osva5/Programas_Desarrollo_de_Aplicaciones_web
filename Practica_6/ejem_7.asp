<%@ Language="VBScript" %>
<% Option Explicit %>

<%
Dim Obj_Conn, Obj_RS, Registro

Set Obj_Conn = Server.CreateObject("ADODB.Connection")
Set Obj_RS = Server.CreateObject("ADODB.Recordset")

Obj_Conn.Open "DSN=Alumnos"

Obj_RS.CursorLocation = 3
Obj_RS.Open "Datos_Alumnos", Obj_Conn, 3, 3

If Obj_RS.EOF Then
    Response.Write "<H2>No existen registros</H2>"
Else

    Obj_RS.PageSize = 9

    If IsEmpty(Session("Pagina")) Then
        Session("Pagina") = 1
    End If

    If Request("Pagina") = "Pagina Siguiente" Then
        Session("Pagina") = Session("Pagina") + 1
    End If

    If Request("Pagina") = "Pagina Anterior" Then
        Session("Pagina") = Session("Pagina") - 1
    End If

    If Session("Pagina") < 1 Then
        Session("Pagina") = 1
    End If

    If Session("Pagina") > Obj_RS.PageCount Then
        Session("Pagina") = Obj_RS.PageCount
    End If

    Obj_RS.AbsolutePage = Session("Pagina")
%>

<TABLE BORDER="1" ALIGN="CENTER">
    <TR>
        <TH>DNI</TH>
        <TH>NOMBRE</TH>
        <TH>APELLIDOS</TH>
        <TH>DIRECCION</TH>
        <TH>TELEFONO</TH>
    </TR>

<%
    Registro = 0

    While Registro < Obj_RS.PageSize And Not Obj_RS.EOF
%>

    <TR>
        <TD><%=Obj_RS("DNI")%></TD>
        <TD><%=Obj_RS("Nombre")%></TD>
        <TD><%=Obj_RS("Apellidos")%></TD>
        <TD><%=Obj_RS("Direccion")%></TD>
        <TD><%=Obj_RS("Telefono")%></TD>
    </TR>

<%
        Registro = Registro + 1
        Obj_RS.MoveNext
    Wend
%>

</TABLE>

<BR>

<FORM METHOD="POST" ACTION="ejem_6.asp">

<% If Session("Pagina") > 1 Then %>
    <INPUT TYPE="SUBMIT"
           NAME="Pagina"
           VALUE="Pagina Anterior">
<% End If %>

<% If Session("Pagina") < Obj_RS.PageCount Then %>
    <INPUT TYPE="SUBMIT"
           NAME="Pagina"
           VALUE="Pagina Siguiente">
<% End If %>

</FORM>

<CENTER>
Página <%=Session("Pagina")%> de <%=Obj_RS.PageCount%>
</CENTER>

<%
End If

Obj_RS.Close
Obj_Conn.Close

Set Obj_RS = Nothing
Set Obj_Conn = Nothing
%>