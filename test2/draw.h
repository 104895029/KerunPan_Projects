void normalize(float *v);
void crossProduct(float *a, float *b, float *c, float *n);
void changeColor(float cx, float cy, float cz);
void drawPlane(float *a, float *b, float *c, float *d);
void drawCubeModel();

/*
	Normalize function for the cross product below
*/
void normalize(float *v){
  float mg;
  int i;

  mg = sqrt((double)(v[0]*v[0]+v[1]*v[1]+v[2]*v[2]));

  if(mg != 0)
    for(i=0; i<3; i++)
      v[i]/=mg;		
  else{
    printf("division by Zero\n");
    exit(0);
  }
}


/* 
	Cross product to find the normal,
	then saved in n after we normalize.
*/
void crossProduct(float *a, float *b, float *c, float *n){

  n[0] = (b[1]-a[1])*(c[2]-a[2])-(b[2]-a[2])*(c[1]-a[1]);
  n[1] = (b[2]-a[2])*(c[0]-a[0])-(b[0]-a[0])*(c[2]-a[2]);
  n[2] = (b[0]-a[0])*(c[1]-a[1])-(b[1]-a[1])*(c[0]-a[0]);

  normalize(n);
}


/* 
	This function applies colour to your lighting
	by changing the diffuse colour.
*/
void changeColor(float cx, float cy, float cz){

        float amb[] = {0.2, 0.2, 0.2, 1.0};
        float diff[] = {cx, cy, cz, 1.0};
        float spec[] = {1.0, 1.0, 1.0, 1.0};

        glMaterialfv(GL_FRONT_AND_BACK, GL_AMBIENT, amb);
        glMaterialfv(GL_FRONT_AND_BACK, GL_DIFFUSE, diff);
        glMaterialfv(GL_FRONT_AND_BACK, GL_SPECULAR, spec);
        glMaterialf(GL_FRONT_AND_BACK, GL_SHININESS, 100);

        glLightModeli(GL_LIGHT_MODEL_LOCAL_VIEWER, GL_TRUE);
}


/*
	Draw a quadrilateral and apply the normals for shading.
	This function takes 4 verticles (a, b, c and d)
	each with 3 components (x, y, z).
*/
void drawPlane(float *a, float *b, float *c, float *d){
        float n[3];
        crossProduct(a, b, c, n);
        glNormal3fv(n);

        glBegin(GL_POLYGON);

	glVertex3fv(a); /* half the quadrilateral (Triangle) */
	glVertex3fv(b);
	glVertex3fv(c);

        glEnd();

        crossProduct(a, c, d, n);
        glNormal3fv(n);

        glBegin(GL_POLYGON);

	glVertex3fv(a); /* Other half of quadrilateral */
	glVertex3fv(c);
	glVertex3fv(d);

        glEnd();
}


/*
	Draw a simple cube.
	For each face of the cube, we do the following:
		1. Define the 4 vertices of the wall (a, b, c and d)
		2. We use "room" as the size of the cube/wall
		3. call "drawPlane" which will draw the wall and apply normals
		4. "changeColor" will apply the colour change to the lighting.
*/
void drawCubeModel() {
        float a[3], b[3], c[3], d[3];

        changeColor(0, 0, .7);

        a[0] = -room; a[1] = room;       a[2] = room;
        b[0] = room;  b[1] = room;       b[2] = room;
        c[0] = room;  c[1] = -room;       c[2] = room;
        d[0] = -room; d[1] = -room;       d[2] = room;
        drawPlane(a, b, c, d);

        a[0] = room; a[1] = room;       a[2] = -room;
        b[0] = -room;  b[1] = room;     b[2] = -room;
        c[0] = -room;  c[1] = -room;    c[2] = -room;
        d[0] = room; d[1] = -room;      d[2] = -room;
        drawPlane(a, b, c, d);

        a[0] = -room; a[1] = room;       a[2] = room;
        b[0] = room;  b[1] = room;       b[2] = room;
        c[0] = room;  c[1] = room;       c[2] = -room;
        d[0] = -room; d[1] = room;       d[2] = -room;
        drawPlane(a, b, c, d);

        a[0] = -room; a[1] = -room;       a[2] = room;
        b[0] = room;  b[1] = -room;       b[2] = room;
        c[0] = room;  c[1] = -room;       c[2] = -room;
        d[0] = -room; d[1] = -room;       d[2] = -room;
        drawPlane(a, b, c, d);

        a[0] = -room; a[1] = room;       a[2] = room;
        b[0] = -room;  b[1] = -room;       b[2] = room;
        c[0] = -room;  c[1] = -room;       c[2] = -room;
        d[0] = -room; d[1] = room;       d[2] = -room;
        drawPlane(a, b, c, d);

        a[0] = room; a[1] = -room;       a[2] = room;
        b[0] = room;  b[1] = room;       b[2] = room;
        c[0] = room;  c[1] = room;       c[2] = -room;
        d[0] = room; d[1] = -room;       d[2] = -room;
        drawPlane(a, b, c, d);
}