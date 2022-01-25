package algo;

public class Product {
	private String name;
	private String description;
	private long date;
	private int ID;
	private float referencement;
	private String[] tag;
	public Product(String name, String description, long date, int iD, int referencement, String[] tag) {
		super();
		this.name = name;
		this.description = description;
		this.date = date;
		ID = iD;
		this.referencement = referencement;
		this.tag = tag;
	}

	
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public String getDescription() {
		return description;
	}
	public void setDescription(String description) {
		this.description = description;
	}
	public long getDate() {
		return date;
	}
	public void setDate(long date) {
		this.date = date;
	}
	public int getID() {
		return ID;
	}
	public void setID(int iD) {
		ID = iD;
	}
	public float getReferencement() {
		return referencement;
	}
	public void setReferencement(int referencement) {
		this.referencement = referencement;
	}
	public String[] getTag() {
		return tag;
	}
	public void setTag(String[] tag) {
		this.tag = tag;
	}
}
