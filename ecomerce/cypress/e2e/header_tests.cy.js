describe("Header Tests", () => {
  beforeEach(() => {
    cy.visit("http://localhost/mk_time/ecomerce/includes/header.php");
  });

  // Navbar Contains
  it("contains the word My 'E-Commerce Site'", () => {
    cy.get("h1").should("contain", "My E-Commerce Site");
  });

  it("contains the word Home", () => {
    cy.get(".home").should("contain", "Home");
  });
});
