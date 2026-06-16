<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejemplo simple 1</title>
</head>
<body>
    <% IF Request. Form="" THEN %>
        <H3> ELECCION DE SEMINARIOS </H3><BR>
        <FORM ACTION= "ejem_9.asp" METHOD=POST>

        <TABLE><TR>
        <TD> Nombre:</TD> <TD><INPUT NAME="V_Nombre"></TD></TR>
        <TD> Apellido:</TD> <TD><INPUT NAME="V_Apellido"></TD></TR>
        <TD> Matricula:</TD> <TD><INPUT NAME="V_Matrícula"></TD></TR>
        <TD> e-mail:</TD> <TD><INPUT NAME="V_Correo"></TD></TR>
            </TABLE>
                Seminarios elegidos:<BR>
                <INPUT NAME="V_Seminario"><BR>
                <INPUT NAME="V_Seminario"><BR>
                <INPUT NAME="V_Seminario"><BR>
                <INPUT NAME="V_Seminario"><BR><BR>
                <INPUT TYPE=SUBMIT>
                <INPUT TYPE=RESET>
            </FORM>
    <% ELSE %>
        <H1> Datos introducidos por el usuario: </HI> <BR>
        Nombre: <%=Request.Form("V_Nombre")%><BR>
        Apellido: <%=Request.Form("V_Apellido")%><BR>
        Matricula: <%=Request.Form("V_Matricula")%><BR>
        e-mail: <%=Request.Form("V_Correo")%><BR>
        Seminario 1: <%=Request.Form("V_Seminario")(1)%><BR>
        Semnario 2: <%=Request.Form("V_Seminario")(2)%><BR>
        Seminario 3: <%=Request.Form("V_Seminario")(3)%><BR>
        Seminario 4: <%=Request.Form("V_Seminario")(4)%><BR>
        <BR><H2> Todo junto: </H2><BR>
        <%=Request.Form%><BR><BR>
    <% END IF %>   

</body>
</html>