create table usuarios(
    us_id serial primary key,
    us_pri_nombre varchar(100),
    us_seg_nombre varchar(100),
    us_pri_apellido varchar(100),
    us_seg_apellido varchar(100),
    us_telefono integer,
    us_direccion varchar(250),
    us_dpi varchar(13),
    us_correo varchar(100),
    us_contrasenia lvarchar(1056),
    us_token lvarchar,
    us_fecha_creacion date default today,
    us_fecha_contrasenia date default today,
    us_foto lvarchar(2056),
    us_situacion char(1)
);



create table asig_permiso(
    asig_id serial primary key,
    asig_usuario integer,
    asig_aplicacion integer,
    asig_permiso integer,
    asig_fecha date default today,
    asig_us_asignado integer,
    asig_motivo varchar(250),
    asig_situacion char(1)
);

alter table asig_permiso add constraint (foreign key(asig_usuario)
references usuarios(us_id) constraint fk_asig_us)

alter table asig_permiso add constraint (foreign key(asig_aplicacion)
references aplicacion(ap_id) constraint fk_asig_ap)

alter table asig_permiso add constraint (foreign key(asig_permiso)
references permisos(per_id) constraint fk_asig_per)



create table aplicacion(
    ap_id serial primary key,
    ap_nombre_lg varchar(150),
    ap_nombre_md varchar(100),
    ap_nombre_ct varchar(50),
    ap_fecha_creacion date default today,
    ap_situacion char(1)
);



create table permisos(
    per_id serial primary key,
    per_aplicacion integer,
    per_nombre_permiso varchar(250),
    per_clave_permiso varchar(250),
    per_descripcion varchar(250),
    per_fecha date default today,
    per_situacion char(1)
);

alter table permisos add constraint (foreign key(per_aplicacion)
references aplicacion(ap_id) constraint fk_per_ap)



create table historial_act(
    his_id serial primary key,
    his_usuario integer,
    his_fecha datetime year to minute,
    his_ruta integer,
    his_ejecucion lvarchar,
    his_situacion char(1)
);

alter table historial_act add constraint (foreign key(his_usuario)
references usuarios(us_id) constraint fk_his_us)

alter table historial_act add constraint (foreign key(his_ruta)
references rutas(rut_id) constraint fk_his_rut)



create table rutas(
    rut_id serial primary key,
    rut_aplicacion integer,
    rut_ruta lvarchar,
    rut_descripcion varchar(250),
    rut_situacion char(1)
);

alter table rutas add constraint (foreign key(rut_aplicacion)
references aplicacion(ap_id) constraint fk_rut_ap)