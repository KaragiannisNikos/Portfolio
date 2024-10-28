describe("Login Tests", () => {
  beforeEach(() => {
    cy.visit("http://localhost/ecomerce/login.php");
  });

  // Login contains
  it('Contains "Login"', () => {
    cy.get("h2").should("contain", "Login");
  });

  it('Contains "email"', () => {
    cy.get("[data-cy=email]").should("contain", "Email");
  });

  it('Contains "address"', () => {
    cy.get("[data-cy=email]").should("contain", "Address");
  });

  it('Contains "password"', () => {
    cy.get("[data-cy=password]").should("contain", "Password");
  });

  it('Contains "Login"', () => {
    cy.get("[data-cy=submit_login]").should("contain", "Login");
  });

  it('Contains "dont have an account"', () => {
    cy.get("[data-cy=no_account]").should("contain", "Don't have an account?");
  });

  /*it('Contains "Sign up"', () => {
    cy.get("[data-cy=sign]").should("contain", "Sign Up");
  });*/

  // Sign up url
  it("Shoud direct you to the register page", () => {
    cy.get("[data-cy=sign]").click();
    cy.visit("http://localhost/ecomerce/register.php");
  });

  // To not allow to login with a missing input.
  it("not allow to login without email.", () => {
    cy.get("[data-cy=submit_login]").click();
  });

  it("not allow to login without password.", () => {
    cy.get("[id=email]").type("nik@os.com");
    cy.get("[data-cy=submit_login]").click();
  });

  // To login
  it("allow to login.", () => {
    cy.get("[id=email]").type("nik@os.com");
    cy.get("[id=password]").type("12345");
    cy.get("[data-cy=submit_login]").click();
  });
});
