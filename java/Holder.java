package algo;

public class Holder {
	private Product product;
	private float score;
	public Holder(Product product, float score) {
		super();
		this.product = product;
		this.score = score;
	}
	public Product getProduct() {
		return product;
	}
	public void setProduct(Product product) {
		this.product = product;
	}
	public float getScore() {
		return score;
	}
	public void setScore(int score) {
		this.score = score;
	}
}
