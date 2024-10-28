describe("template spec", () => {
  beforeEach(() => {
    cy.visit("http://localhost/ecomerce/register.php");
  });

  // Register contains
  it('Contains "Register"', () => {
    cy.get("h2").should("contain", "Register");
  });

  it('Contains "First"', () => {
    cy.get("[data-cy=firstName]").should("contain", "First");
  });

  it('Contains "Name:"', () => {
    cy.get("[data-cy=firstName]").should("contain", "Name:");
  });

  it('Contains "Last"', () => {
    cy.get("[data-cy=lastName]").should("contain", "Last");
  });

  it('Contains "Name:"', () => {
    cy.get("[data-cy=lastName]").should("contain", "Name:");
  });

  it('Contains "email"', () => {
    cy.get("[data-cy=r_email]").should("contain", "Email");
  });

  it('Contains "password"', () => {
    cy.get("[data-cy=r_password]").should("contain", "Password");
  });

  it('Contains "Register"', () => {
    cy.get("[data-cy=submit_register]").should("contain", "Register");
  });

  /*it('Contains "already have an account"', () => {
    cy.get("[data-cy=account]").should("contain", "Already have an account?");
  });*/

  /*it('Contains "Login"', () => {
    cy.get("[data-cy=login]").should("contain", "Login");
  });

  // Login url
  it("Shoud direct you to the login page", () => {
    cy.get("[data-cy=login]").click();
    cy.visit("http://localhost/ecomerce/login.php");
  });*/

  // To not allow to register with a missing input.
  it("not allow to register without firstname.", () => {
    cy.get("[data-cy=submit_register]").click();
  });

  it("not allow to register without lastname.", () => {
    cy.get("[data-cy=firstName").type("nik");
    cy.get("[data-cy=submit_register]").click();
  });

  it("not allow to register without email.", () => {
    cy.get("[data-cy=firstName]").type("test");
    cy.get("[data-cy=lastName]").type("test");
    cy.get("[data-cy=submit_register]").click();
  });

  it("not allow to login without password.", () => {
    cy.get("[data-cy=firstName]").type("test");
    cy.get("[data-cy=lastName]").type("test");
    cy.get("[data-cy=r_email]").type("nik@os.com");
    cy.get("[data-cy=submit_register]").click();
  });

  // To register
  it("not allow to login without password.", () => {
    cy.get("[data-cy=firstName]").type("test");
    cy.get("[data-cy=lastName]").type("test");
    cy.get("[data-cy=r_email]").type("nik@os.com");
    cy.get("[data-cy=r_password]").type("test");
    cy.get("[data-cy=submit_register]").click();
  });
});
